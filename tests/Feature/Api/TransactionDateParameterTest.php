<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionDateParameterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_numeric_year_month_or_date_does_not_break_the_transaction_list(): void
    {
        $user = $this->loginAsApiUser();
        $book = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2026-10-05', 'book_id' => $book->id, 'creator_id' => $user->id]);

        $queries = ['?month=abc', '?year=abc', '?date=abc', '?month=abc&year=abc', '?date=99&month=10&year=2026'];

        foreach ($queries as $query) {
            $this->getJson(route('api.transactions.index').$query);

            $this->assertSame(200, $this->response->getStatusCode(), "GET /api/transactions{$query} did not return 200.");
        }
    }

    /** @test */
    public function a_single_digit_month_is_padded_and_does_not_leak_other_months(): void
    {
        $user = $this->loginAsApiUser();
        $book = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2026-01-05', 'description' => 'January transaction', 'book_id' => $book->id, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2026-10-05', 'description' => 'October transaction', 'book_id' => $book->id, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2026-11-05', 'description' => 'November transaction', 'book_id' => $book->id, 'creator_id' => $user->id]);

        $this->getJson(route('api.transactions.index').'?year=2026&month=1');

        $this->seeJson(['description' => 'January transaction']);
        $this->dontSeeJson(['description' => 'October transaction']);
        $this->dontSeeJson(['description' => 'November transaction']);
    }

    /** @test */
    public function a_valid_year_and_month_still_filters_the_transactions(): void
    {
        $user = $this->loginAsApiUser();
        $book = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2026-09-05', 'description' => 'September transaction', 'book_id' => $book->id, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2026-10-05', 'description' => 'October transaction', 'book_id' => $book->id, 'creator_id' => $user->id]);

        $this->getJson(route('api.transactions.index').'?year=2026&month=10');

        $this->assertSame(200, $this->response->getStatusCode());
        $this->seeJson(['description' => 'October transaction']);
        $this->dontSeeJson(['description' => 'September transaction']);
    }
}
