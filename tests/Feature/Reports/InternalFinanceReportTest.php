<?php

namespace Tests\Feature\Reports;

use App\Models\Book;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalFinanceReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_only_see_transaction_summary_until_today_if_the_selected_month_year_is_current_month_year()
    {
        Carbon::setTestNow('2024-10-20');

        $user = $this->loginAsUser();
        $currentMonth = today()->format('m');
        $currentYear = today()->format('Y');
        $next2Days = today()->addDays(2)->format('Y-m-d');
        $book = factory(Book::class)->create();
        $forwardDateTransaction = factory(Transaction::class)->create([
            'date' => $next2Days,
            'description' => 'Forward date transaction',
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('reports.finance.summary');
        $this->dontSee('Forward date transaction');

        $this->visitRoute('reports.finance.summary', ['month' => $currentMonth, 'year' => $currentYear]);
        $this->dontSee('Forward date transaction');

        Carbon::setTestNow();
    }

    /** @test */
    public function user_only_see_transaction_detail_until_today_if_the_selected_month_year_is_current_month_year()
    {
        Carbon::setTestNow('2024-10-20');

        $user = $this->loginAsUser();
        $currentMonth = today()->format('m');
        $currentYear = today()->format('Y');
        $next2Days = today()->addDays(2)->format('Y-m-d');
        $book = factory(Book::class)->create();
        $forwardDateTransaction = factory(Transaction::class)->create([
            'date' => $next2Days,
            'description' => 'Forward date transaction',
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('reports.finance.detailed');
        $this->dontSee('Forward date transaction');

        $this->visitRoute('reports.finance.detailed', ['month' => $currentMonth, 'year' => $currentYear]);
        $this->dontSee('Forward date transaction');

        Carbon::setTestNow();
    }
}
