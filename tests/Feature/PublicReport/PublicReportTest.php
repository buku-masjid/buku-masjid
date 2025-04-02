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
}
