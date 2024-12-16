<?php

namespace Tests\Feature\Transactions;

use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransactionFilesUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_transaction_files()
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
        $this->visitRoute('transactions.show', $transaction);
        $this->seeElement('a', ['id' => 'upload_files-transaction-'.$transaction->id]);

        $this->click('upload_files-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.show', [$transaction, 'action' => 'upload_files']);

        $this->submitForm(__('file.upload'), [
            'files' => [
                public_path('screenshots/01-monthly-report-for-public.jpg'),
            ],
            'description' => 'Deskripsi file yang diuplod.',
        ]);

        $this->seeText(__('file.uploaded'));
        $this->seeRouteIs('transactions.show', $transaction);

        $this->assertCount(1, $transaction->files);

        $this->seeInDatabase('files', [
            'fileable_id' => $transaction->id,
            'fileable_type' => 'transactions',
            'type_code' => 'image',
            'title' => null,
            'description' => 'Deskripsi file yang diuplod.',
        ]);

        $file = $transaction->files->first();
        Storage::assertExists('files/'.$file->filename);
    }
}
