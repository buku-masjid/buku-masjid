<?php

namespace Tests\Feature\Transactions;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionEntryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create([
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'color' => config('masjid.income_color'),
        ]);
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seeRouteIs('transactions.index', ['action' => 'add-income', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_income'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => 1, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function user_can_create_a_spending_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_spending'));
        $this->seeRouteIs('transactions.index', ['action' => 'add-spending', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_spending'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.spending_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => 0, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
        ]);
    }

    /** @test */
    public function new_transaction_book_id_filled_with_the_current_active_book()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create();
        $activeBook = factory(Book::class)->create();
        $category = factory(Category::class)->create([
            'book_id' => $activeBook->id,
            'creator_id' => $user->id,
            'color' => config('masjid.income_color'),
        ]);
        session()->put('active_book_id', $activeBook->id);

        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seeRouteIs('transactions.index', ['action' => 'add-income', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_income'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => 1, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'book_id' => $activeBook->id,
        ]);
    }
}
