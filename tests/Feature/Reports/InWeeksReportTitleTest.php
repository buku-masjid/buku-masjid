<?php

namespace Tests\Feature\Reports;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Fakes\FakeMpdfWrapper;
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

        $this->assertHeadingContains('Per Pekan '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Per Pekan '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_categorized_page_shows_the_period_in_the_heading_and_the_page_title()
    {
        $this->visitRoute('reports.finance.categorized', $this->dateRangeQuery);

        $this->assertHeadingContains('Per Kategori '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Per Kategori '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_weeks_detailed_page_shows_the_period_in_the_heading_and_the_page_title()
    {
        $this->visitRoute('reports.finance.detailed', $this->dateRangeQuery);

        $this->assertHeadingContains('Rincian Transaksi '.$this->expectedPeriodText);
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
        $this->assertHeadingContains('Kas Pekanan '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Kas Pekanan '.$this->expectedPeriodText);

        $this->visitRoute('reports.finance.categorized', $this->dateRangeQuery);
        $this->assertHeadingContains('Kas Per Kategori '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Kas Per Kategori '.$this->expectedPeriodText);

        $this->visitRoute('reports.finance.detailed', $this->dateRangeQuery);
        $this->assertHeadingContains('Kas Rincian '.$this->expectedPeriodText);
        $this->assertPageTitleContains('Kas Rincian '.$this->expectedPeriodText);
    }

    /** @test */
    public function the_in_months_reports_keep_the_period_out_of_their_titles()
    {
        $book = auth()->activeBook();
        $book->report_periode_code = Book::REPORT_PERIODE_IN_MONTHS;
        $book->save();

        $expectedHeadings = [
            'summary' => __('report.monthly'),
            'categorized' => __('report.categorized_transactions'),
            'detailed' => __('report.weekly'),
        ];

        foreach ($expectedHeadings as $reportType => $expectedHeading) {
            $this->visitRoute('reports.finance.'.$reportType);

            $this->seeInElement('h1.page-title', $expectedHeading);
            $this->dontSeeInElement('h1.page-title', __('time.period'));
            $this->assertStringNotContainsString(
                __('time.period'),
                $this->extractTitle($this->normalizeWhitespace($this->response->getContent())),
                "The {$reportType} title of the in_months report should not show the period."
            );
        }
    }

    private function assertHeadingContains(string $expectedText): void
    {
        $this->seeInElement('h1.page-title', $expectedText);
    }

    private function assertPageTitleContains(string $expectedText): void
    {
        $this->assertStringContainsString($expectedText, $this->extractTitle($this->normalizeWhitespace($this->response->getContent())), 'The page title should show the report period.');
    }

    private function assertPdfContains(string $routeName, string $expectedText): void
    {
        $fakeWrapper = new FakeMpdfWrapper;
        $this->app->instance('mpdf.wrapper', $fakeWrapper);

        $this->get(route($routeName, $this->dateRangeQuery));

        $html = $this->normalizeWhitespace($fakeWrapper->renderedHtml);

        $this->assertStringContainsString($expectedText, $this->extractHeading($html), 'The PDF heading should show the report period.');
        $this->assertStringContainsString($expectedText, $this->extractTitle($html), 'The PDF title should show the report period.');
    }

    private function extractHeading(string $html): string
    {
        preg_match('/<h2 class="text-center strong">(.*?)<\/h2>/s', $html, $matches);

        return $matches[1] ?? '';
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
