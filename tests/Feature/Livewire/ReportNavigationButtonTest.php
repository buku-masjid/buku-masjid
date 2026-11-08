<?php

namespace Tests\Feature\Livewire;

use App\Models\Book;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportNavigationButtonTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->loginAsUser();
        $this->book = factory(Book::class)->create([
            'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
            'report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS,
        ]);
        $this->createTransaction('2026-10-01');
        Carbon::setTestNow('2026-10-30 10:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** @test */
    public function the_week_buttons_link_while_the_target_range_is_within_the_bounds(): void
    {
        $this->visitRoute('public_reports.finance.summary', ['start_date' => '2026-11-02', 'end_date' => '2026-11-08']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringContainsString('start_date=2026-11-09', $this->response->getContent(), 'The next week button should link to the next week.');
        $this->assertStringContainsString('start_date=2026-10-26', $this->response->getContent(), 'The prev week button should link to the previous week.');
    }

    /** @test */
    public function the_next_week_button_is_not_a_link_when_it_would_leave_the_upper_bound(): void
    {
        $this->visitRoute('public_reports.finance.summary', ['start_date' => '2027-10-18', 'end_date' => '2027-10-24']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringNotContainsString('start_date=2027-10-25', $this->response->getContent(), 'The next week button should be disabled past the upper bound.');
        $this->assertStringContainsString('start_date=2027-10-11', $this->response->getContent(), 'The prev week button should still link.');
    }

    /** @test */
    public function the_prev_week_button_is_not_a_link_when_it_would_leave_the_lower_bound(): void
    {
        $this->visitRoute('public_reports.finance.summary', ['start_date' => '2025-10-06', 'end_date' => '2025-10-12']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringNotContainsString('start_date=2025-09-29', $this->response->getContent(), 'The prev week button should be disabled before the lower bound.');
        $this->assertStringContainsString('start_date=2025-10-13', $this->response->getContent(), 'The next week button should still link.');
    }

    /** @test */
    public function the_month_buttons_link_while_the_target_month_is_within_the_bounds(): void
    {
        $this->book->report_periode_code = Book::REPORT_PERIODE_IN_MONTHS;
        $this->book->save();

        $this->visitRoute('public_reports.finance.summary', ['year' => '2026', 'month' => '10']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringContainsString('month=11', $this->response->getContent(), 'The next month button should link to the next month.');
        $this->assertStringContainsString('month=09', $this->response->getContent(), 'The prev month button should link to the previous month.');
    }

    /** @test */
    public function the_next_month_button_is_not_a_link_when_it_would_leave_the_upper_bound(): void
    {
        $this->book->report_periode_code = Book::REPORT_PERIODE_IN_MONTHS;
        $this->book->save();

        $this->visitRoute('public_reports.finance.summary', ['year' => '2027', 'month' => '09']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringNotContainsString('month=10', $this->response->getContent(), 'The next month button should be disabled past the upper bound.');
    }

    /** @test */
    public function the_prev_month_button_is_not_a_link_when_it_would_leave_the_lower_bound(): void
    {
        $this->book->report_periode_code = Book::REPORT_PERIODE_IN_MONTHS;
        $this->book->save();

        $this->visitRoute('public_reports.finance.summary', ['year' => '2025', 'month' => '10']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringNotContainsString('month=09', $this->response->getContent(), 'The prev month button should be disabled before the lower bound.');
    }

    /** @test */
    public function a_huge_year_does_not_break_the_month_buttons(): void
    {
        $this->book->report_periode_code = Book::REPORT_PERIODE_IN_MONTHS;
        $this->book->save();

        $this->visitRoute('public_reports.finance.summary', ['year' => '366203092', 'month' => '08']);

        $this->assertSame(200, $this->response->getStatusCode());
    }

    private function createTransaction(string $date): Transaction
    {
        return factory(Transaction::class)->create([
            'date' => $date,
            'book_id' => $this->book->id,
            'creator_id' => $this->user->id,
        ]);
    }
}
