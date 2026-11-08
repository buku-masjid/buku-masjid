<?php

namespace Tests\Unit\Helpers;

use App\Models\Book;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportDateBoundsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_bounds_the_range_one_year_before_and_after_the_book_transactions(): void
    {
        Carbon::setTestNow('2025-01-15 10:00:00');

        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2024-03-01', 'book_id' => $book->id, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2026-08-31', 'book_id' => $book->id, 'creator_id' => $user->id]);

        [$minAllowedStart, $maxAllowedEnd] = get_report_date_bounds($book);

        $this->assertSame('2023-03-01 00:00:00', $minAllowedStart->format('Y-m-d H:i:s'));
        $this->assertSame('2027-08-31 23:59:59', $maxAllowedEnd->format('Y-m-d H:i:s'));

        Carbon::setTestNow();
    }

    /** @test */
    public function it_includes_the_running_year_window_when_the_book_has_old_transactions(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');

        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2022-05-01', 'book_id' => $book->id, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2022-12-31', 'book_id' => $book->id, 'creator_id' => $user->id]);

        [$minAllowedStart, $maxAllowedEnd] = get_report_date_bounds($book);

        $this->assertSame('2021-05-01', $minAllowedStart->format('Y-m-d'));
        $this->assertSame('2027-10-30', $maxAllowedEnd->format('Y-m-d'));

        Carbon::setTestNow();
    }

    /** @test */
    public function it_includes_the_running_year_window_when_the_book_has_forward_dated_transactions(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');

        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2030-01-15', 'book_id' => $book->id, 'creator_id' => $user->id]);

        [$minAllowedStart, $maxAllowedEnd] = get_report_date_bounds($book);

        $this->assertSame('2025-10-30', $minAllowedStart->format('Y-m-d'));
        $this->assertSame('2031-01-15', $maxAllowedEnd->format('Y-m-d'));

        Carbon::setTestNow();
    }

    /** @test */
    public function it_falls_back_to_the_running_year_window_for_a_book_without_transactions(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');

        $book = factory(Book::class)->create();

        [$minAllowedStart, $maxAllowedEnd] = get_report_date_bounds($book);

        $this->assertSame('2025-10-30', $minAllowedStart->format('Y-m-d'));
        $this->assertSame('2027-10-30', $maxAllowedEnd->format('Y-m-d'));

        Carbon::setTestNow();
    }

    /** @test */
    public function it_only_uses_the_transactions_of_the_given_book(): void
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $otherBook = factory(Book::class)->create();
        factory(Transaction::class)->create(['date' => '2026-01-01', 'book_id' => $book->id, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-01', 'book_id' => $otherBook->id, 'creator_id' => $user->id]);

        [$minAllowedStart] = get_report_date_bounds($book);

        $this->assertSame('2025-01-01', $minAllowedStart->format('Y-m-d'));
    }

    /** @test */
    public function it_falls_back_to_the_running_year_window_without_a_book(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');

        [$minAllowedStart, $maxAllowedEnd] = get_report_date_bounds(null);

        $this->assertSame('2025-10-30', $minAllowedStart->format('Y-m-d'));
        $this->assertSame('2027-10-30', $maxAllowedEnd->format('Y-m-d'));

        Carbon::setTestNow();
    }
}
