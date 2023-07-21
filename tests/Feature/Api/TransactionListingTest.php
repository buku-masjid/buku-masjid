<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_their_transaction_list()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);

        $this->getJson(route('api.transactions.index'));

        $this->seeJson([
            'date' => $transaction->date,
            'amount' => $transaction->amount,
            'amount_string' => $transaction->amount_string,
            'description' => $transaction->description,
            'category' => optional($transaction->category)->name,
            'category_color' => optional($transaction->category)->color,
            'difference' => $transaction->amount_string,
        ]);
    }

    /** @test */
    public function user_can_get_a_transaction_detail()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);

        $this->getJson(route('api.transactions.show', $transaction));

        $this->seeJson([
            'date' => $transaction->date,
            'amount' => $transaction->amount,
            'amount_string' => $transaction->amount_string,
            'description' => $transaction->description,
            'in_out' => $transaction->in_out,
            'category_id' => $transaction->category_id,
            'category' => optional($transaction->category)->name,
            'category_color' => optional($transaction->category)->color,
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
        ]);
    }
}
