<?php

namespace Tests\Feature\PublicReport;

use App\Models\Book;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicReportDateBoundTest extends TestCase
{
    use RefreshDatabase;

    private $routeNames = [
        'public_reports.index',
        'public_reports.finance.summary',
        'public_reports.finance.categorized',
        'public_reports.finance.detailed',
    ];

    /** @test */
    public function invalid_year_and_month_parameters_render_the_default_range(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_IN_MONTHS);

        $invalidQueries = [
            ['year' => '366203092', 'month' => '08'],
            ['year' => '-1', 'month' => '08'],
            ['year' => 'abc', 'month' => '08'],
            ['year' => '12345', 'month' => '08'],
            ['year' => '0', 'month' => '08'],
            ['year' => '999999999', 'month' => '08'],
            ['year' => '2026', 'month' => 'abc'],
            ['year' => '2026', 'month' => '13'],
            ['year' => '2026', 'month' => '99'],
        ];

        foreach ($this->routeNames as $routeName) {
            $this->visitRoute($routeName);
            $defaultRange = $this->renderedRange();

            foreach ($invalidQueries as $query) {
                $this->visitRoute($routeName, $query);
                $this->assertSame(200, $this->response->getStatusCode(), "{$routeName} with ".json_encode($query).' did not return 200.');
                $this->assertSame($defaultRange, $this->renderedRange(), "{$routeName} with ".json_encode($query).' shifted the report range.');
            }
        }

        Carbon::setTestNow();
    }

    /** @test */
    public function a_huge_year_does_not_break_the_month_navigation_buttons(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_IN_MONTHS);

        foreach ($this->routeNames as $routeName) {
            $this->visitRoute($routeName, ['year' => '366203092', 'month' => '08']);
            $this->assertSame(200, $this->response->getStatusCode(), "{$routeName} with a huge year did not return 200.");
        }

        Carbon::setTestNow();
    }

    /** @test */
    public function a_valid_year_and_month_still_render_that_month(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_IN_MONTHS);

        $this->visitRoute('public_reports.finance.summary', ['year' => '2026', 'month' => '10']);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringContainsString('1 Okt 2026', $this->renderedRange());
    }

    /** @test */
    public function a_date_outside_the_book_bounds_is_not_found(): void
    {
        $this->publicBook(Book::REPORT_PERIODE_ALL_TIME);
        $this->createTransaction('2024-03-01');
        $this->createTransaction('2026-08-31');

        $this->get(route('public_reports.finance.summary', ['start_date' => '2023-03-01', 'end_date' => '2026-08-31']));
        $this->assertSame(200, $this->response->getStatusCode(), 'The exact lower bound should be reachable.');

        $this->get(route('public_reports.finance.summary', ['start_date' => '2023-02-28', 'end_date' => '2026-08-31']));
        $this->assertSame(404, $this->response->getStatusCode(), 'One day before the lower bound should be a 404.');
    }

    /** @test */
    public function an_end_date_beyond_the_book_bounds_is_not_found(): void
    {
        Carbon::setTestNow('2024-01-15 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_ALL_TIME);
        $this->createTransaction('2024-03-01');
        $this->createTransaction('2026-08-31');

        $this->get(route('public_reports.finance.summary', ['start_date' => '2024-03-01', 'end_date' => '2027-08-31']));
        $this->assertSame(200, $this->response->getStatusCode(), 'The exact upper bound should be reachable.');

        $this->get(route('public_reports.finance.summary', ['start_date' => '2024-03-01', 'end_date' => '2027-09-01']));
        $this->assertSame(404, $this->response->getStatusCode(), 'One day after the upper bound should be a 404.');

        Carbon::setTestNow();
    }

    /** @test */
    public function a_year_outside_the_book_bounds_is_not_found(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_IN_MONTHS);
        $this->createTransaction('2026-02-01');

        $this->get(route('public_reports.finance.summary', ['year' => '2026', 'month' => '02']));
        $this->assertSame(200, $this->response->getStatusCode());

        $this->get(route('public_reports.finance.summary', ['year' => '2015', 'month' => '02']));
        $this->assertSame(404, $this->response->getStatusCode(), 'A year before the lower bound should be a 404.');

        Carbon::setTestNow();
    }

    /** @test */
    public function a_book_without_transactions_uses_the_running_year_window(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_ALL_TIME);

        $this->get(route('public_reports.finance.summary', ['start_date' => '2025-10-30', 'end_date' => '2027-10-30']));
        $this->assertSame(200, $this->response->getStatusCode());

        $this->get(route('public_reports.finance.summary', ['start_date' => '2025-10-30', 'end_date' => '2029-10-30']));
        $this->assertSame(404, $this->response->getStatusCode());

        Carbon::setTestNow();
    }

    /** @test */
    public function the_bounds_are_taken_from_the_active_book_only(): void
    {
        $this->publicBook(Book::REPORT_PERIODE_ALL_TIME);
        $this->createTransaction('2026-02-01');

        $otherBook = factory(Book::class)->create(['report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC]);
        factory(Transaction::class)->create(['date' => '2015-02-01', 'book_id' => $otherBook->id, 'creator_id' => $this->creator->id]);

        $this->get(route('public_reports.finance.summary', ['start_date' => '2016-02-01', 'end_date' => '2026-03-01']));
        $this->assertSame(404, $this->response->getStatusCode(), 'An old range of another book should not unlock the active book.');
    }

    /** @test */
    public function the_default_public_report_page_of_a_stale_book_still_renders(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_ALL_TIME);
        $this->createTransaction('2022-05-01');
        $this->createTransaction('2022-12-31');

        foreach ($this->routeNames as $routeName) {
            $this->visitRoute($routeName);
            $this->assertSame(200, $this->response->getStatusCode(), "{$routeName} of a stale book should not be a 404.");
        }

        Carbon::setTestNow();
    }

    /** @test */
    public function garbage_date_parameters_are_ignored_instead_of_being_not_found(): void
    {
        Carbon::setTestNow('2026-10-30 10:00:00');
        $this->publicBook(Book::REPORT_PERIODE_ALL_TIME);
        $this->createTransaction('2026-02-01');

        foreach (['0000-00-00', '2026-02-30', 'not-a-date'] as $garbageDate) {
            $this->visitRoute('public_reports.finance.summary', ['start_date' => $garbageDate, 'end_date' => $garbageDate]);
            $this->assertSame(200, $this->response->getStatusCode(), json_encode($garbageDate).' should be ignored, not rejected.');
        }

        Carbon::setTestNow();
    }

    private function publicBook(string $periodeCode): Book
    {
        $this->creator = $this->createUser();

        return factory(Book::class)->create([
            'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
            'report_periode_code' => $periodeCode,
        ]);
    }

    private function createTransaction(string $date): Transaction
    {
        return factory(Transaction::class)->create([
            'date' => $date,
            'book_id' => Book::first()->id,
            'creator_id' => $this->creator->id,
        ]);
    }

    private function renderedRange(): string
    {
        return trim(preg_replace('/\s+/', ' ', $this->crawler->filter('.text-sm-end')->first()->text()));
    }
}
