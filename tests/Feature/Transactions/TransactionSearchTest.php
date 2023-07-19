<?php

namespace Tests\Feature\Transactions;

use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_visit_the_search_transactions_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['book_id' => $book->id]);

        $this->visitRoute('transactions.index');
        $this->see($transaction->description);
        $this->seeElement('a', ['href' => route('transactions.index')]);

        $this->click(__('app.search'));
        $this->seeRouteIs('transaction_search.index');
        $this->dontSee($transaction->description);
    }

    /** @test */
    public function user_can_search_for_transactions()
    {
        $user = $this->loginAsUser();

        $lastMonth = today()->subDays(31);
        $lastMonthNumber = $lastMonth->format('m');
        $lastMonthYear = $lastMonth->format('Y');
        $lastMonthDate = $lastMonth->format('Y-m-d');
        $foundTransaction = factory(Transaction::class)->create([
            'date' => $lastMonthDate,
            'description' => 'internet bill',
            'creator_id' => $user->id,
        ]);
        $missingTransaction = factory(Transaction::class)->create([
            'date' => today(),
            'description' => 'The missing transaction',
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('transaction_search.index', ['search_query' => 'internet bill']);
        $this->see($foundTransaction->description);
        $this->dontSee($missingTransaction->description);
    }
}
