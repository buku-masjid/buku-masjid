<?php

namespace Tests\Feature\PublicReport;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function default_book_is_not_visible_in_the_public_report_if_visibility_is_internal(): void
    {
        $book = factory(Book::class)->create(['report_visibility_code' => 'public']);

        $this->visitRoute('public_reports.index');
        $this->seeRouteIs('public_reports.index');
        $this->seeElement('a', ['id' => 'show-book-1']);

        $book->report_visibility_code = 'internal';
        $book->save();

        $this->visitRoute('public_reports.index');
        $this->seePageIs('/');
        $this->dontSeeElement('a', ['id' => 'show-book-1']);
    }

    /** @test */
    public function visitor_redirected_to_public_report_home_page_if_the_default_book_visibility_is_internal(): void
    {
        $book = factory(Book::class)->create(['report_visibility_code' => 'public']);

        $this->visitRoute('public_reports.finance.summary');
        $this->seeRouteIs('public_reports.finance.summary');
        $this->seeElement('select', ['name' => 'month']);

        $this->visitRoute('public_reports.finance.categorized');
        $this->seeRouteIs('public_reports.finance.categorized');
        $this->seeElement('select', ['name' => 'month']);

        $this->visitRoute('public_reports.finance.detailed');
        $this->seeRouteIs('public_reports.finance.detailed');
        $this->seeElement('select', ['name' => 'month']);

        $book->report_visibility_code = 'internal';
        $book->save();

        $this->visitRoute('public_reports.finance.summary');
        $this->seePageIs('/');

        $this->visitRoute('public_reports.finance.categorized');
        $this->seePageIs('/');

        $this->visitRoute('public_reports.finance.detailed');
        $this->seePageIs('/');
    }

    /** @test */
    public function public_report_pages_ignore_invalid_date_parameters(): void
    {
        $routeNames = [
            'public_reports.index',
            'public_reports.finance.summary',
            'public_reports.finance.categorized',
            'public_reports.finance.detailed',
        ];
        $invalidDateQueries = [
            ['end_date' => 'not-a-date'],
            ['end_date' => '2026-13-45'],
            ['start_date' => 'not-a-date'],
            ['start_date' => '2026-13-45'],
            ['start_date' => '', 'end_date' => ''],
            ['start_date' => ['not', 'a', 'string']],
        ];
        $book = factory(Book::class)->create(['report_visibility_code' => 'public']);

        foreach ([Book::REPORT_PERIODE_IN_MONTHS, Book::REPORT_PERIODE_IN_WEEKS, Book::REPORT_PERIODE_ALL_TIME] as $reportPeriodeCode) {
            $book->report_periode_code = $reportPeriodeCode;
            $book->save();

            foreach ($routeNames as $routeName) {
                foreach ($invalidDateQueries as $query) {
                    $this->get(route($routeName, $query));
                    $this->assertEquals(
                        200,
                        $this->response->getStatusCode(),
                        "{$routeName} with ".json_encode($query)." on {$reportPeriodeCode} book did not return 200."
                    );
                }
            }
        }
    }

    /** @test */
    public function public_report_pages_still_use_valid_date_parameters(): void
    {
        $book = factory(Book::class)->create([
            'report_visibility_code' => 'public',
            'report_periode_code' => Book::REPORT_PERIODE_ALL_TIME,
        ]);

        $this->visitRoute('public_reports.finance.summary', ['start_date' => '2026-10-05', 'end_date' => '2026-10-11']);
        $this->seeElement('input', ['name' => 'start_date', 'value' => '2026-10-05']);
        $this->seeElement('input', ['name' => 'end_date', 'value' => '2026-10-11']);
    }
}
