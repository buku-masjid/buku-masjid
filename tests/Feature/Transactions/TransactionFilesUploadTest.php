<?php

namespace Tests\Feature\Transactions;

use App\Jobs\Files\OptimizeImage;
use App\Models\Book;
use App\Services\SystemInfo\DiskUsageService;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\Fakes\FakeDiskUsageService;
use Tests\TestCase;

class TransactionFilesUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_transaction_files()
    {
        Bus::fake();
        Storage::fake(config('filesystem.default'));
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);
        $this->visitRoute('transactions.show', $transaction);
        $this->seeElement('a', ['id' => 'upload_files-transaction-'.$transaction->id]);

        $this->click('upload_files-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.show', [$transaction, 'action' => 'upload_files']);

        $this->submitForm(__('file.upload'), [
            'files' => [
                public_path('screenshots/01-monthly-report-for-public.jpg'),
            ],
            'title' => 'Document title',
            'description' => 'Document file description',
        ]);

        $this->seeText(__('file.uploaded'));
        $this->seeRouteIs('transactions.show', $transaction);

        $this->assertCount(1, $transaction->files);

        $this->seeInDatabase('files', [
            'fileable_id' => $transaction->id,
            'fileable_type' => 'transactions',
            'type_code' => 'raw_image',
            'title' => 'Document title',
            'description' => 'Document file description',
        ]);

        $file = $transaction->files->first();
        Storage::assertExists($file->file_path);

        Bus::assertDispatched(OptimizeImage::class, function ($job) use ($file) {
            return $job->file->id = $file->id;
        });
    }

    /** @test */
    public function user_cannot_upload_transaction_files_when_disk_is_full()
    {
        Bus::fake();
        Storage::fake(config('filesystem.default'));

        $this->app->instance(DiskUsageService::class, new FakeDiskUsageService);

        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);

        view()->share('isDiskFull', true);

        $this->visitRoute('transactions.show', $transaction);

        $this->see(__('transaction.disk_is_full'));
        $this->seeElement('a', [
            'id' => 'upload_files-transaction-'.$transaction->id,
            'class' => 'btn btn-success mr-2 disabled',
        ]);
    }

    /** @test */
    public function user_can_edit_transaction_file_attribute()
    {
        Storage::fake(config('filesystem.default'));
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);
        Storage::makeDirectory('files');
        copy(public_path('screenshots/01-monthly-report-for-public.jpg'), Storage::path('files/landscape_image.jpg'));
        $file = $transaction->files()->create([
            'file_path' => 'files/landscape_image.jpg',
            'type_code' => 'raw_image',
        ]);
        Storage::assertExists($file->file_path);

        $this->visitRoute('transactions.show', $transaction);
        $this->seeElement('a', ['id' => 'edit-file-'.$file->id]);
        $this->click('edit-file-'.$file->id);
        $this->seeRouteIs('transactions.show', [$transaction, 'action' => 'edit_file', 'file_id' => $file->id]);
        $this->submitForm(__('app.update'), [
            'title' => 'Different document title',
            'description' => 'Changed document description',
        ]);
        $this->seeRouteIs('transactions.show', $transaction);

        $this->seeText(__('file.updated'));

        $this->seeInDatabase('files', [
            'id' => $file->id,
            'title' => 'Different document title',
            'description' => 'Changed document description',
        ]);
    }

    /** @test */
    public function security_prevent_user_from_editing_other_transactions_file_from_the_current_transaction()
    {
        Storage::fake(config('filesystem.default'));
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id, 'book_id' => $book->id]);
        $transactionFile = $transaction->files()->create(['file_path' => 'file.jpg', 'type_code' => 'raw_image']);
        $otherTransaction = factory(Transaction::class)->create(['creator_id' => $user->id, 'book_id' => $book->id]);
        $otherTransactionFile = $otherTransaction->files()->create(['file_path' => 'file.jpg', 'type_code' => 'raw_image']);

        $this->visitRoute('transactions.show', [$transaction, 'action' => 'edit_file', 'file_id' => $transactionFile->id]);
        $this->seeElement('input', ['type' => 'submit', 'value' => __('file.update')]);

        $this->visitRoute('transactions.show', [$otherTransaction, 'action' => 'edit_file', 'file_id' => $otherTransactionFile->id]);
        $this->seeElement('input', ['type' => 'submit', 'value' => __('file.update')]);

        $this->visitRoute('transactions.show', [$transaction, 'action' => 'edit_file', 'file_id' => $otherTransactionFile->id]);
        $this->dontSeeElement('input', ['type' => 'submit', 'value' => __('file.update')]);
    }

    /** @test */
    public function user_can_delete_transaction_file()
    {
        Storage::fake(config('filesystem.default'));
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);

        Storage::makeDirectory('files');
        copy(public_path('screenshots/01-monthly-report-for-public.jpg'), Storage::path('files/landscape_image.jpg'));
        $file = $transaction->files()->create([
            'file_path' => 'files/landscape_image.jpg',
            'type_code' => 'raw_image',
        ]);
        Storage::assertExists($file->file_path);

        $this->visitRoute('transactions.show', $transaction);
        $this->seeElement('button', ['id' => 'delete-file-'.$file->id]);
        $this->press('delete-file-'.$file->id);
        $this->seeRouteIs('transactions.show', $transaction);

        $this->seeText(__('file.deleted'));
        $this->dontSeeInDatabase('files', ['id' => $file->id]);

        Storage::assertMissing($file->file_path);
    }

    /** @test */
    public function deleting_transaction_will_also_deletes_transaction_files()
    {
        Storage::fake(config('filesystem.default'));
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);
        $this->visitRoute('transactions.show', [$transaction, 'action' => 'upload_files']);
        $this->submitForm(__('file.upload'), [
            'files' => [
                public_path('screenshots/01-monthly-report-for-public.jpg'),
            ],
            'title' => 'Document title',
            'description' => 'Document file description',
        ]);

        $file = $transaction->files->first();
        Storage::assertExists($file->file_path);

        $this->visitRoute('transactions.show', $transaction);
        $this->click('edit-transaction-'.$file->id);
        $this->seeRouteIs('transactions.edit', $transaction);
        $this->click('del-transaction-'.$file->id);
        $this->seeRouteIs('transactions.edit', [$transaction, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->seeText(__('transaction.deleted'));
        $this->dontSeeInDatabase('files', ['id' => $file->id]);

        Storage::assertMissing($file->file_path);
    }

    /** @test */
    public function security_prevent_user_from_deleting_other_transactions_file_from_the_current_transaction()
    {
        Storage::fake(config('filesystem.default'));
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id, 'book_id' => $book->id]);
        $transactionFile = $transaction->files()->create(['file_path' => 'file.jpg', 'type_code' => 'raw_image']);
        $otherTransaction = factory(Transaction::class)->create(['creator_id' => $user->id, 'book_id' => $book->id]);
        $otherTransactionFile = $otherTransaction->files()->create(['file_path' => 'file.jpg', 'type_code' => 'raw_image']);

        $this->delete(route('transactions.files.destroy', [$transaction, $transactionFile]));
        $this->seeStatusCode(302);

        $this->delete(route('transactions.files.destroy', [$transaction, $otherTransactionFile]));
        $this->seeStatusCode(404);

        $this->delete(route('transactions.files.destroy', [$otherTransaction, $otherTransactionFile]));
        $this->seeStatusCode(302);
    }
}
