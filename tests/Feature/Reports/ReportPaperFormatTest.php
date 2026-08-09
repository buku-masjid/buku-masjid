<?php

namespace Tests\Feature\Reports;

use App\Models\Book;
use App\Models\Setting as SettingModel;
use Facades\App\Helpers\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ReportPaperFormatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function summary_pdf_uses_a4_when_paper_format_query_is_a4()
    {
        $this->loginAsUser();
        factory(Book::class)->create();

        $this->assertPdfFormat('A4', ['paper_format' => 'A4']);
    }

    /** @test */
    public function summary_pdf_uses_legal_when_paper_format_query_is_legal()
    {
        $this->loginAsUser();
        factory(Book::class)->create();

        $this->assertPdfFormat('Legal', ['paper_format' => 'Legal']);
    }

    /** @test */
    public function summary_pdf_uses_book_default_when_no_paper_format_query()
    {
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        session()->put('active_book_id', $book->id);
        $this->setBookSetting($book, 'report_paper_format', 'Legal');

        $this->assertPdfFormat('Legal', []);
    }

    /** @test */
    public function summary_pdf_uses_book_default_when_invalid_paper_format_query()
    {
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        session()->put('active_book_id', $book->id);
        $this->setBookSetting($book, 'report_paper_format', 'A4');

        $this->assertPdfFormat('A4', ['paper_format' => 'Others']);
    }

    /** @test */
    public function admin_can_update_book_default_report_paper_format()
    {
        $this->loginAsUser();
        $book = factory(Book::class)->create();

        $this->visitRoute('books.edit', $book);
        $this->submitForm(__('book.update'), [
            'name' => $book->name,
            'report_paper_format' => 'Legal',
        ]);

        $this->assertEquals('Legal', SettingModel::where('model_id', $book->id)
            ->where('model_type', $book->getMorphClass())
            ->where('key', 'report_paper_format')
            ->value('value'));
    }

    /** @test */
    public function book_edit_page_displays_default_report_paper_format()
    {
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $this->setBookSetting($book, 'report_paper_format', 'Legal');

        $this->visitRoute('books.edit', $book);
        $this->seeElement('select', ['name' => 'report_paper_format']);
        $this->seeIsSelected('report_paper_format', 'Legal');
    }

    /** @test */
    public function book_show_page_displays_default_report_paper_format()
    {
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $this->setBookSetting($book, 'report_paper_format', 'Legal');

        $this->visitRoute('books.show', $book);
        $this->see('Legal');
    }

    private function setBookSetting(Book $book, string $key, string $value): void
    {
        SettingModel::create([
            'model_id' => $book->id,
            'model_type' => $book->getMorphClass(),
            'key' => $key,
            'value' => $value,
        ]);
    }

    private function assertPdfFormat(string $expectedFormat, array $query)
    {
        $capturedFormat = null;

        $fakeWrapper = new class($expectedFormat) {
            public $expectedFormat;
            public $capturedFormat;

            public function __construct($expectedFormat)
            {
                $this->expectedFormat = $expectedFormat;
            }

            public function loadView($view, $data = [], $mergeData = [], $config = [])
            {
                $this->capturedFormat = $config['format'] ?? null;

                return $this;
            }

            public function stream($filename = null)
            {
                $response = new Response;
                $response->header('Content-Type', 'application/pdf');

                return $response;
            }
        };

        $this->app->instance('mpdf.wrapper', $fakeWrapper);

        $this->get(route('reports.finance.summary_pdf', $query));

        $this->assertEquals($expectedFormat, $fakeWrapper->capturedFormat);
    }
}
