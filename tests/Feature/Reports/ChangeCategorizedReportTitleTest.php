<?php

namespace Tests\Feature\Reports;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeCategorizedReportTitleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_change_book_report_title()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Ramadhan 2023']);

        $this->visitRoute('reports.finance.categorized', ['year' => 2023, 'month' => 12]);
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.finance.categorized', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'month' => 12,
            'nonce' => $book->nonce,
            'year' => 2023,
        ]);

        $this->submitForm(__('book.change_report_title'), [
            'report_titles' => ['finance_categorized' => 'Judul Laporan'],
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->seeRouteIs('reports.finance.categorized', [
            'month' => 12,
            'year' => 2023,
        ]);

        $this->seeText(__('report.title_updated'));
        $this->seeText('Judul Laporan');
        $this->seeInDatabase('books', [
            'id' => $book->id,
            'report_titles' => json_encode(['finance_categorized' => 'Judul Laporan']),
        ]);
    }

    /** @test */
    public function other_title_should_not_be_reset()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create([
            'name' => 'Ramadhan 2023',
            'report_titles' => ['finance_summary' => 'Laporan Ringkasan', 'finance_categorized' => 'Judul Per Kategori'],
        ]);

        $this->visitRoute('reports.finance.categorized');
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.finance.categorized', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->submitForm(__('book.change_report_title'), [
            'report_titles' => ['finance_categorized' => 'Judul Laporan'],
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->seeRouteIs('reports.finance.categorized');

        $this->seeText(__('report.title_updated'));
        $this->seeText('Judul Laporan');
        $this->seeInDatabase('books', [
            'id' => $book->id,
            'report_titles' => json_encode(['finance_summary' => 'Laporan Ringkasan', 'finance_categorized' => 'Judul Laporan']),
        ]);
    }

    /** @test */
    public function book_report_title_field_is_filled_with_the_existing_title_text()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Ramadhan 2023']);

        $this->visitRoute('reports.finance.categorized');
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.finance.categorized', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);
        $this->seeElement('input', [
            'type' => 'text',
            'name' => 'report_titles[finance_categorized]',
            'value' => __('report.categorized_transactions'),
        ]);
    }

    /** @test */
    public function reset_default_report_title()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Ramadhan 2023']);

        $this->visitRoute('reports.finance.categorized');
        $this->seeElement('a', ['id' => 'change_report_title']);

        $this->click('change_report_title');

        $this->seeRouteIs('reports.finance.categorized', [
            'action' => 'change_report_title',
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->submitForm(__('book.reset_report_title'), [
            'report_titles' => ['finance_categorized' => 'Judul Laporan'],
            'book_id' => $book->id,
            'nonce' => $book->nonce,
        ]);

        $this->seeRouteIs('reports.finance.categorized');

        $this->seeText(__('report.title_updated'));
        $this->seeText(__('report.categorized_transactions'));
        $this->seeInDatabase('books', [
            'id' => $book->id,
            'report_titles' => json_encode(['finance_categorized' => null]),
        ]);
    }
}
