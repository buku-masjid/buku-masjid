<?php

namespace Tests\Feature\Transactions;

use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_transaction_list_in_transaction_index_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);

        $this->visitRoute('transactions.index');
        $this->see($transaction->amount);
    }

    /** @test */
    public function user_can_see_transaction_list_based_on_the_selected_book()
    {
        $user = $this->loginAsUser();
        $otherBook = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'description' => 'Specific description',
            'book_id' => $otherBook->id,
            'creator_id' => $user->id,
        ]);
        $selectedBook = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('transactions.index');
        $this->see($transaction->description);

        $this->press('switch_book_'.$selectedBook->id);
        $this->seeInSession('active_book_id', $selectedBook->id);
        $this->dontSee($transaction->description);
    }

    /** @test */
    public function user_can_see_transaction_list_by_selected_month_and_year()
    {
        $user = $this->loginAsUser();
        $lastMonth = today()->subDays(31);
        $lastMonthNumber = $lastMonth->format('m');
        $lastMonthYear = $lastMonth->format('Y');
        $lastMonthDate = $lastMonth->format('Y-m-d');
        $book = factory(Book::class)->create();
        $lastMonthTransaction = factory(Transaction::class)->create([
            'date' => $lastMonthDate,
            'description' => 'Last month Transaction',
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('transactions.index');
        $this->dontSee($lastMonthTransaction->description);

        $this->visitRoute('transactions.index', ['month' => $lastMonthNumber, 'year' => $lastMonthYear]);
        $this->see($lastMonthTransaction->description);
    }

    /** @test */
    public function user_can_see_transaction_list_by_selected_category_and_search_query()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create();
        $todayDate = today()->format('Y-m-d');
        factory(Transaction::class)->create([
            'date' => $todayDate,
            'description' => 'Unlisted transaction',
            'category_id' => null,
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        factory(Transaction::class)->create([
            'date' => $todayDate,
            'description' => 'Today listed transaction',
            'category_id' => $category->id,
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('transactions.index');
        $this->see('Unlisted transaction');
        $this->see('Today listed transaction');

        $this->visitRoute('transactions.index', ['query' => 'listed', 'category_id' => $category->id]);
        $this->seeRouteIs('transactions.index', ['category_id' => $category->id, 'query' => 'listed']);
        $this->dontSee('Unlisted transaction');
        $this->see('Today listed transaction');
    }

    /** @test */
    public function transaction_list_for_this_month_by_default()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $thisMonthTransaction = factory(Transaction::class)->create([
            'date' => today()->format('Y-m-d'),
            'description' => 'Today Transaction',
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        $lastMonthDate = today()->subDays(31)->format('Y-m-d');
        $lastMonthTransaction = factory(Transaction::class)->create([
            'date' => $lastMonthDate,
            'description' => 'Last month transaction',
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('transactions.index');
        $this->see($thisMonthTransaction->description);
        $this->dontSee($lastMonthTransaction->description);
    }

    /** @test */
    public function user_can_see_transaction_list_by_selected_book()
    {
        $user = $this->loginAsUser();
        $defaultBook = factory(Book::class)->create(['creator_id' => $user->id]);
        $selectedBook = factory(Book::class)->create(['creator_id' => $user->id]);
        $todayDate = today()->format('Y-m-d');
        factory(Transaction::class)->create([
            'date' => $todayDate,
            'description' => 'Unlisted transaction',
            'book_id' => $defaultBook->id,
            'creator_id' => $user->id,
        ]);
        factory(Transaction::class)->create([
            'date' => $todayDate,
            'description' => 'Today listed transaction',
            'book_id' => $selectedBook->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('transactions.index');
        $this->see('Unlisted transaction');
        $this->dontSee('Today listed transaction');

        session()->put('active_book_id', $selectedBook->id);
        $this->visitRoute('transactions.index');
        $this->dontSee('Unlisted transaction');
        $this->see('Today listed transaction');
    }
}
