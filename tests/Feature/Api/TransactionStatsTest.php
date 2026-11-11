<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionStatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Convert a money string produced by format_number() back into a float,
     * using the same currency configuration as the application.
     */
    private function toNumber(string $formatted): float
    {
        $value = trim($formatted);
        $isNegative = str_starts_with($value, '-');
        $value = str_replace(['-', ' '], '', $value);
        $value = str_replace(config('money.thousands_separator'), '', $value);
        $value = str_replace(config('money.decimal_separator'), '.', $value);

        return $isNegative ? -(float) $value : (float) $value;
    }

    private function makeBook(): Book
    {
        $user = $this->loginAsApiUser();
        $book = factory(Book::class)->create();
        session()->put('active_book_id', $book->id);

        return $book;
    }

    private function makeTransaction(Book $book, string $date, float $amount, int $inOut = Transaction::TYPE_INCOME): Transaction
    {
        return factory(Transaction::class)->create([
            'book_id' => $book->id,
            'date' => $date,
            'amount' => $amount,
            'in_out' => $inOut,
        ]);
    }

    /** @test */
    public function start_balance_is_the_balance_before_the_earliest_transaction_of_the_period()
    {
        $book = $this->makeBook();
        $this->makeTransaction($book, '2026-10-15', 100000);
        $this->makeTransaction($book, '2026-11-05', 50000);
        $this->makeTransaction($book, '2026-11-20', 20000, Transaction::TYPE_SPENDING);

        $this->getJson(route('api.transactions.index').'?year=2026&month=11');

        $stats = $this->response->json()['stats'];

        $this->assertSame(format_number(100000), $stats['start_balance']);
        $this->assertSame(format_number(30000), $stats['difference']);
        $this->assertSame(format_number(130000), $stats['end_balance']);
    }

    /** @test */
    public function start_balance_plus_difference_equals_end_balance()
    {
        $book = $this->makeBook();
        $this->makeTransaction($book, '2026-10-15', 100000);
        $this->makeTransaction($book, '2026-11-05', 50000);
        $this->makeTransaction($book, '2026-11-20', 20000, Transaction::TYPE_SPENDING);
        $this->makeTransaction($book, '2026-12-01', 999999);

        $this->getJson(route('api.transactions.index').'?year=2026&month=11');

        $stats = $this->response->json()['stats'];

        $start = $this->toNumber($stats['start_balance']);
        $difference = $this->toNumber($stats['difference']);
        $end = $this->toNumber($stats['end_balance']);

        $this->assertEqualsWithDelta($end, $start + $difference, 0.01);
    }

    /** @test */
    public function the_stats_do_not_change_when_the_period_has_more_transactions_than_a_single_page()
    {
        $book = $this->makeBook();
        $this->makeTransaction($book, '2026-10-15', 100000);

        foreach (range(1, 120) as $day) {
            $this->makeTransaction($book, '2026-11-'.str_pad((string) ($day % 28 + 1), 2, '0', STR_PAD_LEFT), 1000);
        }

        $this->getJson(route('api.transactions.index').'?year=2026&month=11');

        $stats = $this->response->json()['stats'];

        $this->assertSame(format_number(100000), $stats['start_balance']);
        $this->assertSame(format_number(120000), $stats['difference']);
        $this->assertSame(format_number(220000), $stats['end_balance']);
        $this->assertEqualsWithDelta(
            $this->toNumber($stats['end_balance']),
            $this->toNumber($stats['start_balance']) + $this->toNumber($stats['difference']),
            0.01
        );
    }

    /** @test */
    public function the_stats_of_a_period_are_not_affected_by_transactions_of_other_periods()
    {
        $book = $this->makeBook();
        $this->makeTransaction($book, '2026-09-01', 700000);
        $this->makeTransaction($book, '2026-11-02', 5000);
        $this->makeTransaction($book, '2026-12-31', 900000);

        $this->getJson(route('api.transactions.index').'?year=2026&month=11');

        $stats = $this->response->json()['stats'];

        $this->assertSame(format_number(700000), $stats['start_balance']);
        $this->assertSame(format_number(5000), $stats['income_total']);
        $this->assertSame(format_number(0), $stats['spending_total']);
        $this->assertSame(format_number(705000), $stats['end_balance']);
    }

    /** @test */
    public function the_stats_stay_consistent_when_a_category_filter_is_applied()
    {
        $book = $this->makeBook();
        $category = factory(Category::class)->create(['book_id' => $book->id]);
        $otherCategory = factory(Category::class)->create(['book_id' => $book->id]);

        factory(Transaction::class)->create([
            'book_id' => $book->id, 'category_id' => $category->id,
            'date' => '2026-10-10', 'amount' => 30000, 'in_out' => Transaction::TYPE_INCOME,
        ]);
        factory(Transaction::class)->create([
            'book_id' => $book->id, 'category_id' => $otherCategory->id,
            'date' => '2026-10-11', 'amount' => 900000, 'in_out' => Transaction::TYPE_INCOME,
        ]);
        factory(Transaction::class)->create([
            'book_id' => $book->id, 'category_id' => $category->id,
            'date' => '2026-11-05', 'amount' => 12000, 'in_out' => Transaction::TYPE_SPENDING,
        ]);

        $this->getJson(route('api.transactions.index').'?year=2026&month=11&category_id='.$category->id);

        $stats = $this->response->json()['stats'];

        $this->assertSame(format_number(30000), $stats['start_balance']);
        $this->assertSame(format_number(-12000), $stats['difference']);
        $this->assertSame(format_number(18000), $stats['end_balance']);
        $this->assertEqualsWithDelta(
            $this->toNumber($stats['end_balance']),
            $this->toNumber($stats['start_balance']) + $this->toNumber($stats['difference']),
            0.01
        );
    }

    /** @test */
    public function a_request_without_a_period_filter_keeps_working_as_before()
    {
        $book = $this->makeBook();
        $today = date('Y-m-d');
        $this->makeTransaction($book, '2020-01-01', 40000);
        $this->makeTransaction($book, $today, 25000);

        $this->getJson(route('api.transactions.index'));

        $this->assertSame(200, $this->response->getStatusCode());
        $stats = $this->response->json()['stats'];

        $this->assertSame(format_number(40000), $stats['start_balance']);
        $this->assertSame(format_number(65000), $stats['end_balance']);
    }
}
