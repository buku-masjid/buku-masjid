<?php

namespace Tests\Feature\Api\Transactions;

use App\Jobs\Files\OptimizeImage;
use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private function prepare($role = 'finance', $authenticate = true)
    {
        Bus::fake();
        Storage::fake(config('filesystem.default'));
        $user = $this->createUser($role);
        $book = factory(Book::class)->create();
        config(['masjid.default_book_id' => $book->id]);
        $transaction = factory(Transaction::class)->create([
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return [$user, $transaction];
    }

    private function createAttachment(Transaction $transaction, $filePath = 'files/2026/10/15/nota.jpg', $title = 'Nota')
    {
        Storage::put($filePath, 'nota-content');

        return $transaction->files()->create([
            'type_code' => 'image',
            'file_path' => $filePath,
            'title' => $title,
            'description' => null,
        ]);
    }

    private function jsonServer($user = null)
    {
        $server = ['HTTP_ACCEPT' => 'application/json'];
        if (!is_null($user)) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer '.$user->api_token;
        }

        return $server;
    }

    private function uploadPost($url, array $fields, array $files, $user = null)
    {
        return $this->call('POST', $url, $fields, [], $files, $this->jsonServer($user));
    }

    private function uploadPatch($url, array $fields, array $files, $user = null)
    {
        return $this->call('PATCH', $url, $fields, [], $files, $this->jsonServer($user));
    }

    /** @test */
    public function user_can_upload_one_image_to_an_existing_transaction()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [UploadedFile::fake()->image('nota.jpg')]],
            $user
        );

        $this->seeStatusCode(201);
        $this->assertCount(1, $transaction->files()->get());
        $file = $transaction->files()->get()->first();
        $this->assertSame($file->id, $this->response->json('data.0.id'));
        $this->seeInDatabase('files', [
            'fileable_id' => $transaction->id,
            'fileable_type' => 'transactions',
            'type_code' => 'raw_image',
        ]);
        Storage::assertExists($file->file_path);
        Bus::assertDispatched(OptimizeImage::class);
    }

    /** @test */
    public function user_can_upload_two_images_in_one_request()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [
                UploadedFile::fake()->image('nota1.jpg'),
                UploadedFile::fake()->image('nota2.jpg'),
            ]],
            $user
        );

        $this->seeStatusCode(201);
        $this->assertCount(2, $transaction->files()->get());
    }

    /** @test */
    public function uploaded_title_and_description_are_stored_and_returned()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            ['title' => 'Nota kursi', 'description' => 'Pembelian 5 kursi'],
            ['files' => [UploadedFile::fake()->image('nota.jpg')]],
            $user
        );

        $this->seeStatusCode(201);
        $this->seeInDatabase('files', [
            'title' => 'Nota kursi',
            'description' => 'Pembelian 5 kursi',
        ]);
        $this->assertSame('Nota kursi', $this->response->json('data.0.title'));
    }

    /** @test */
    public function upload_rejects_a_non_image_file()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [UploadedFile::fake()->create('nota.pdf', 100)]],
            $user
        );

        $this->seeStatusCode(422);
        $this->assertCount(0, $transaction->files()->get());
    }

    /** @test */
    public function upload_rejects_an_oversized_file()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [UploadedFile::fake()->image('besar.jpg')->size(6000)]],
            $user
        );

        $this->seeStatusCode(422);
    }

    /** @test */
    public function upload_rejects_a_request_without_files()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            [],
            $user
        );

        $this->seeStatusCode(422);
    }

    /** @test */
    public function unauthenticated_upload_is_rejected_with_401()
    {
        [, $transaction] = $this->prepare('finance', false);

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [UploadedFile::fake()->image('nota.jpg')]]
        );

        $this->seeStatusCode(401);
    }

    /** @test */
    public function user_without_finance_or_admin_role_cannot_upload()
    {
        [$user, $transaction] = $this->prepare('chairman');

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [UploadedFile::fake()->image('nota.jpg')]],
            $user
        );

        $this->seeStatusCode(403);
        $this->assertCount(0, $transaction->files()->get());
    }

    /** @test */
    public function upload_to_an_unknown_transaction_returns_404()
    {
        [$user] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', 9999),
            [],
            ['files' => [UploadedFile::fake()->image('nota.jpg')]],
            $user
        );

        $this->seeStatusCode(404);
    }

    /** @test */
    public function attachments_index_returns_the_uploaded_file()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPost(
            route('api.transactions.files.store', $transaction),
            [],
            ['files' => [UploadedFile::fake()->image('nota.jpg')]],
            $user
        );

        $this->getJson(route('api.transactions.files.index', $transaction), $this->jsonServer($user));

        $this->seeStatusCode(200);
        $this->assertCount(1, $this->response->json('data'));
        $this->assertStringContainsString('/storage/', $this->response->json('data.0.url'));
    }

    /** @test */
    public function attachments_index_of_a_transaction_without_files_returns_an_empty_array()
    {
        [$user, $transaction] = $this->prepare();

        $this->getJson(route('api.transactions.files.index', $transaction), $this->jsonServer($user));

        $this->seeStatusCode(200);
        $this->assertSame([], $this->response->json('data'));
    }

    /** @test */
    public function attachments_api_does_not_expose_show()
    {
        $this->assertFalse(Route::has('api.transactions.files.show'));
    }

    /** @test */
    public function user_can_update_an_attachment_title()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction);

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['title' => 'Nota 12 Okt'],
            [],
            $user
        );

        $this->seeStatusCode(200);
        $this->seeInDatabase('files', ['id' => $file->id, 'title' => 'Nota 12 Okt']);
        $this->assertSame($file->id, $this->response->json('data.id'));
    }

    /** @test */
    public function user_can_update_only_the_description_without_clearing_the_title()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction, 'files/2026/10/15/nota.jpg', 'Nota');

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['description' => 'Deskripsi baru'],
            [],
            $user
        );

        $this->seeStatusCode(200);
        $this->seeInDatabase('files', [
            'id' => $file->id,
            'title' => 'Nota',
            'description' => 'Deskripsi baru',
        ]);
    }

    /** @test */
    public function update_without_files_does_not_touch_the_file_path_or_type_code()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction);

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['title' => 'Judul Baru'],
            [],
            $user
        );

        $this->seeStatusCode(200);
        $file->refresh();
        $this->assertSame('files/2026/10/15/nota.jpg', $file->file_path);
        $this->assertSame('image', $file->type_code);
    }

    /** @test */
    public function update_rejects_a_title_longer_than_255_characters()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction, 'files/2026/10/15/nota.jpg', 'Nota');

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['title' => str_repeat('a', 256)],
            [],
            $user
        );

        $this->seeStatusCode(422);
        $this->seeInDatabase('files', ['id' => $file->id, 'title' => 'Nota']);
    }

    /** @test */
    public function unauthenticated_update_is_rejected_with_401()
    {
        [, $transaction] = $this->prepare('finance', false);
        $file = $this->createAttachment($transaction);

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['title' => 'Nota 12 Okt'],
            []
        );

        $this->seeStatusCode(401);
    }

    /** @test */
    public function user_without_finance_or_admin_role_cannot_update()
    {
        [$user, $transaction] = $this->prepare('chairman');
        $file = $this->createAttachment($transaction, 'files/2026/10/15/nota.jpg', 'Nota');

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['title' => 'Nota 12 Okt'],
            [],
            $user
        );

        $this->seeStatusCode(403);
        $this->seeInDatabase('files', ['id' => $file->id, 'title' => 'Nota']);
    }

    /** @test */
    public function update_returns_404_when_the_file_belongs_to_another_transaction()
    {
        [$user, $transactionA] = $this->prepare();
        $transactionB = factory(Transaction::class)->create([
            'creator_id' => $user->id,
            'book_id' => $transactionA->book_id,
        ]);
        $this->createAttachment($transactionA, 'files/2026/10/15/notaA.jpg', 'Nota A');
        $fileB = $this->createAttachment($transactionB, 'files/2026/10/15/notaB.jpg', 'Nota B');

        $this->uploadPatch(
            route('api.transactions.files.update', [$transactionA, $fileB]),
            ['title' => 'Hacked'],
            [],
            $user
        );

        $this->seeStatusCode(404);
        $this->seeInDatabase('files', ['id' => $fileB->id, 'title' => 'Nota B']);
    }

    /** @test */
    public function update_returns_404_when_the_file_does_not_exist()
    {
        [$user, $transaction] = $this->prepare();

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, 9999]),
            ['title' => 'Nota 12 Okt'],
            [],
            $user
        );

        $this->seeStatusCode(404);
    }

    /** @test */
    public function patching_with_files_ignores_the_upload_and_keeps_the_existing_image()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction, 'files/2026/10/15/nota.jpg', 'Nota');

        $this->uploadPatch(
            route('api.transactions.files.update', [$transaction, $file]),
            ['description' => 'Deskripsi baru'],
            ['files' => [UploadedFile::fake()->image('nota-baru.jpg')]],
            $user
        );

        $this->seeStatusCode(200);
        $file->refresh();
        $this->assertSame('files/2026/10/15/nota.jpg', $file->file_path);
        $this->assertSame('image', $file->type_code);
        $this->assertSame('Nota', $file->title);
        $this->assertSame('Deskripsi baru', $file->description);
        Storage::assertExists('files/2026/10/15/nota.jpg');
        Bus::assertNotDispatched(OptimizeImage::class);
        $this->assertCount(1, $transaction->files()->get());
    }

    /** @test */
    public function user_can_delete_an_attachment()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction);

        $this->deleteJson(
            route('api.transactions.files.destroy', [$transaction, $file]),
            ['file_id' => $file->id],
            $this->jsonServer($user)
        );

        $this->seeStatusCode(200);
        $this->seeJson(['message' => __('file.deleted')]);
        $this->dontSeeInDatabase('files', ['id' => $file->id]);
        Storage::assertMissing('files/2026/10/15/nota.jpg');
    }

    /** @test */
    public function delete_requires_the_file_id_echo()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction);

        $this->deleteJson(
            route('api.transactions.files.destroy', [$transaction, $file]),
            [],
            $this->jsonServer($user)
        );

        $this->seeStatusCode(422);
        $this->seeInDatabase('files', ['id' => $file->id]);
        Storage::assertExists('files/2026/10/15/nota.jpg');
    }

    /** @test */
    public function delete_with_a_mismatched_file_id_does_not_delete_the_file()
    {
        [$user, $transaction] = $this->prepare();
        $file = $this->createAttachment($transaction);

        $this->deleteJson(
            route('api.transactions.files.destroy', [$transaction, $file]),
            ['file_id' => $file->id + 1],
            $this->jsonServer($user)
        );

        $this->seeStatusCode(200);
        $this->seeJson(['message' => __('file.undeleted')]);
        $this->seeInDatabase('files', ['id' => $file->id]);
        Storage::assertExists('files/2026/10/15/nota.jpg');
    }

    /** @test */
    public function delete_returns_404_when_the_file_belongs_to_another_transaction()
    {
        [$user, $transactionA] = $this->prepare();
        $transactionB = factory(Transaction::class)->create([
            'creator_id' => $user->id,
            'book_id' => $transactionA->book_id,
        ]);
        $fileB = $this->createAttachment($transactionB, 'files/2026/10/15/notaB.jpg', 'Nota B');

        $this->deleteJson(
            route('api.transactions.files.destroy', [$transactionA, $fileB]),
            ['file_id' => $fileB->id],
            $this->jsonServer($user)
        );

        $this->seeStatusCode(404);
        $this->seeInDatabase('files', ['id' => $fileB->id]);
        Storage::assertExists('files/2026/10/15/notaB.jpg');
    }

    /** @test */
    public function user_without_finance_or_admin_role_cannot_delete()
    {
        [$user, $transaction] = $this->prepare('chairman');
        $file = $this->createAttachment($transaction);

        $this->deleteJson(
            route('api.transactions.files.destroy', [$transaction, $file]),
            ['file_id' => $file->id],
            $this->jsonServer($user)
        );

        $this->seeStatusCode(403);
        $this->seeInDatabase('files', ['id' => $file->id]);
    }

    /** @test */
    public function unauthenticated_delete_is_rejected_with_401()
    {
        [, $transaction] = $this->prepare('finance', false);
        $file = $this->createAttachment($transaction);

        $this->deleteJson(
            route('api.transactions.files.destroy', [$transaction, $file]),
            ['file_id' => $file->id]
        );

        $this->seeStatusCode(401);
    }
}
