<?php

namespace Tests\Feature\Reports;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeInMonthsReportTitleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_change_book_report_title()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Ramadhan 2023']);

        $this->visitRoute('reports.in_months');
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.in_months', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->submitForm(__('book.change_report_title'), [
            'report_titles' => ['in_months' => 'Judul Laporan'],
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->seeRouteIs('reports.in_months');

        $this->seeText(__('report.title_updated'));
        $this->seeText('Judul Laporan');
        $this->seeInDatabase('books', [
            'id' => $book->id,
            'report_titles' => json_encode(['in_months' => 'Judul Laporan']),
        ]);
    }

    /** @test */
    public function book_report_title_field_is_filled_with_the_existing_title_text()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Ramadhan 2023']);

        $this->visitRoute('reports.in_months');
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.in_months', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);
        $this->seeElement('input', [
            'type' => 'text',
            'name' => 'report_titles[in_months]',
            'value' => __('report.monthly'),
        ]);
    }

    /** @test */
    public function reset_default_report_title()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Ramadhan 2023']);

        $this->visitRoute('reports.in_months');
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.in_months', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->submitForm(__('book.reset_report_title'), [
            'report_titles' => ['in_months' => 'Judul Laporan'],
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->seeRouteIs('reports.in_months');

        $this->seeText(__('report.title_updated'));
        $this->seeText(__('report.monthly'));
        $this->seeInDatabase('books', [
            'id' => $book->id,
            'report_titles' => json_encode(['in_months' => null]),
        ]);
    }
}
