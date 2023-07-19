<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookBalanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function book_model_get_balance_method()
    {
        $book = factory(Book::class)->create();

        $this->assertEquals(0.0, $book->getBalance());
    }

    /** @test */
    public function balance_can_returns_current_balance_of_alltime_transactions()
    {
        $book = factory(Book::class)->create();

        // Income transaction
        factory(Transaction::class)->create(['book_id' => $book->id, 'amount' => 10000, 'in_out' => 1]);
        // Spending transaction
        factory(Transaction::class)->create(['book_id' => $book->id, 'amount' => 3000, 'in_out' => 0]);

        // Assert balance with no specific date range
        $this->assertEquals(7000, $book->getBalance());
    }

    /** @test */
    public function balance_can_returns_balance_until_specified_date()
    {
        $book = factory(Book::class)->create();

        // Income transaction
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'book_id' => $book->id]);
        // Spending transaction
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'book_id' => $book->id]);

        // Other transaction after specified date
        factory(Transaction::class)->create(['date' => '2015-01-31', 'amount' => 10000, 'in_out' => 1, 'book_id' => $book->id]);

        // Assert balance until date '2015-01-30'
        $this->assertEquals(6000, $book->getBalance('2015-01-30'));
    }

    /** @test */
    public function balance_can_returns_balance_within_date_ranges()
    {
        $book = factory(Book::class)->create();

        // Other transaction outside date range
        factory(Transaction::class)->create(['date' => '2015-01-01', 'amount' => 900, 'in_out' => 1, 'book_id' => $book->id]);

        // Transaction inside date range
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'book_id' => $book->id]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'book_id' => $book->id]);
        factory(Transaction::class)->create(['date' => '2015-01-31', 'amount' => 10000, 'in_out' => 1, 'book_id' => $book->id]);

        // Assert balance from '2015-01-03' until '2015-01-30'
        $this->assertEquals(16000, $book->getBalance('2015-01-31', '2015-01-03'));
    }

    /** @test */
    public function make_sure_balance_only_for_each_book()
    {
        $book = factory(Book::class)->create();

        // Other transaction outside specified date
        factory(Transaction::class)->create(['date' => '2015-01-01', 'amount' => 900, 'in_out' => 1, 'book_id' => $book->id]);

        // Transactions insde specified date
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'book_id' => $book->id]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'book_id' => $book->id]);

        // Other book's transaction within date range
        factory(Transaction::class)->create(['date' => '2015-01-18', 'amount' => 10000, 'in_out' => 1, 'book_id' => 999]);

        // Assert balance from '2015-01-03' until '2015-01-30'
        $this->assertEquals(6000, $book->getBalance('2015-01-31', '2015-01-03'));
    }

    /** @test */
    public function balance_function_accepts_category_id_parameter()
    {
        $book = factory(Book::class)->create();

        // Other transaction with different category_id
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'book_id' => $book->id, 'category_id' => 1]);

        factory(Transaction::class)->create(['date' => '2015-01-05', 'amount' => 900, 'in_out' => 0, 'book_id' => $book->id, 'category_id' => 2]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 1, 'book_id' => $book->id, 'category_id' => 2]);

        // Assert balance from '2015-01-03' until '2015-01-30' with category_id 2
        $this->assertEquals(3100, $book->getBalance('2015-01-31', '2015-01-03', 2));
    }
}
