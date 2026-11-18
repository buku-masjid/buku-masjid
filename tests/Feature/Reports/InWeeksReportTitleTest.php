<?php

namespace Tests\Feature\Reports;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class InWeeksReportTitleTest extends TestCase
{
    use RefreshDatabase;

    private $dateRangeQuery = ['start_date' => '2026-11-09', 'end_date' => '2026-11-15'];

    private $expectedPeriodText = 'Periode 09 - 15 Nov 2026';

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-11-18 10:00:00');

        $this->loginAsUser();
        $book = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS]);
        session()->put('active_book_id', $book->id);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** @test */
    public function the_in_weeks_summary_page_shows_the_period_in_the_heading_and_the_page_title()
    {
        $this->visitRoute('reports.finance.summary', $this->dateRangeQuery);

        $this->assertPageContains('Per Pekan '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Per Pekan '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_categorized_page_shows_the_period_in_the_heading_and_the_page_title()
    {
        $this->visitRoute('reports.finance.categorized', $this->dateRangeQuery);

        $this->assertPageContains('Per Kategori '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Per Kategori '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_detailed_page_shows_the_period_in_the_heading_and_the_page_title()
    {
        $this->visitRoute('reports.finance.detailed', $this->dateRangeQuery);

        $this->assertPageContains('Rincian Transaksi '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Rincian Transaksi '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_summary_pdf_shows_the_period_in_the_heading_and_the_title()
    {
        $this->assertPdfContains('reports.finance.summary_pdf', 'Per Pekan '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_categorized_pdf_shows_the_period_in_the_heading_and_the_title()
    {
        $this->assertPdfContains('reports.finance.categorized_pdf', 'Per Kategori '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_detailed_pdf_shows_the_period_in_the_heading_and_the_title()
    {
        $this->assertPdfContains('reports.finance.detailed_pdf', 'Rincian Transaksi '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_period_is_appended_to_a_custom_report_title()
    {
        $book = auth()->activeBook();
        $book->report_titles = [
            'finance_summary' => 'Kas Pekanan',
            'finance_categorized' => 'Kas Per Kategori',
            'finance_detailed' => 'Kas Rincian',
        ];
        $book->save();

        $this->visitRoute('reports.finance.summary', $this->dateRangeQuery);
        $this->assertPageContains('Kas Pekanan '.$this->expectedPeriodText);

        $this->visitRoute('reports.finance.categorized', $this->dateRangeQuery);
        $this->assertPageContains('Kas Per Kategori '.$this->expectedPeriodText);

        $this->visitRoute('reports.finance.detailed', $this->dateRangeQuery);
        $this->assertPageContains('Kas Rincian '.$this->expectedPeriodText);
    }

    private function assertPageContains(string $expectedText): void
    {
        $this->assertStringContainsString($expectedText, $this->normalizeWhitespace($this->response->getContent()));
    }

    private function assertPageTitleContains(string $expectedText): void
    {
        $content = $this->normalizeWhitespace($this->response->getContent());
        preg_match('/<title>(.*?)<\/title>/s', $content, $matches);

        $this->assertStringContainsString($expectedText, $matches[1] ?? '', 'The page title should show the report period.');
    }

    private function assertPdfContains(string $routeName, string $expectedText): void
    {
        $fakeWrapper = new class
        {
            public $renderedHtml;

            public function loadView($view, $data = [], $mergeData = [], $config = [])
            {
                $this->renderedHtml = view($view, $data, $mergeData)->render();

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

        $this->get(route($routeName, $this->dateRangeQuery));

        $html = $this->normalizeWhitespace($fakeWrapper->renderedHtml);

        $this->assertStringContainsString($expectedText, $html);
        $this->assertStringContainsString($expectedText, $this->extractTitle($html), 'The PDF title should show the report period.');
    }

    private function extractTitle(string $html): string
    {
        preg_match('/<title>(.*?)<\/title>/s', $html, $matches);

        return $matches[1] ?? '';
    }

    private function normalizeWhitespace(string $content): string
    {
        return preg_replace('/\s+/', ' ', $content);
    }
}
