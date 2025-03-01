<?php

namespace Tests\Feature\Livewire\PublicHome;

use App\Http\Livewire\PublicHome\WeeklyFinancialSummary;
use App\Models\Book;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WeeklyFinancialSummaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_weekly_financial_report_card()
    {
        factory(Book::class)->create();
        $startOfWeekDayDate = now()->startOfWeek()->isoFormat('dddd, D MMMM Y');
        $todayDayDate = now()->isoFormat('dddd, D MMMM Y');
        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span class="date" id="start_week_label">'.__('report.balance_per_date', ['date' => $startOfWeekDayDate]).'</span>')
            ->assertSeeHtml('<span class="date" id="start_week_balance">'.config('money.currency_code').' '.format_number(0).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_spending_total">'.config('money.currency_code').' '.format_number(0).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance_label">'.__('report.today_balance', ['date' => $todayDayDate]).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance">'.config('money.currency_code').' '.format_number(0).'</span>');
    }

    /** @test */
    public function user_cannot_see_report_detail_button_if_book_visibility_is_internal()
    {
        $book = factory(Book::class)->create();
        $startOfWeekDayDate = now()->startOfWeek()->isoFormat('dddd, D MMMM Y');
        $todayDayDate = now()->isoFormat('dddd, D MMMM Y');
        Livewire::test(WeeklyFinancialSummary::class)
            ->assertDontSeeHtml('<a class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 me-3 px-2 py-1" href="'.route('public_reports.finance.detailed').'" role="button">'.__('app.show').'</a>');

        $book->report_visibility_code = 'public';
        $book->save();
        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<a class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 me-3 px-2 py-1" href="'.route('public_reports.index').'" role="button">'.__('app.show').'</a>');
    }

    /** @test */
    public function user_can_see_weekly_financial_report_card()
    {
        factory(Book::class)->create();
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'in_out' => 0,
        ]);

        $this->visit('/');

        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span class="date" id="start_week_balance">'.config('money.currency_code').' '.format_number(0).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_income_total">'.config('money.currency_code').' '.format_number(100000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_spending_total">'.config('money.currency_code').' '.format_number(10000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance">'.config('money.currency_code').' '.format_number(90000).'</span>');
    }

    /** @test */
    public function make_sure_other_books_transactions_are_not_calculated()
    {
        $defaultBook = factory(Book::class)->create();
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'book_id' => $defaultBook->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);
        $anotherBook = factory(Book::class)->create();
        factory(Transaction::class)->create([
            'amount' => 35000,
            'date' => $today,
            'book_id' => $anotherBook->id,
            'in_out' => 1,
        ]);

        $this->visit('/');

        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span class="date" id="start_week_balance">'.config('money.currency_code').' '.format_number(0).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_income_total">'.config('money.currency_code').' '.format_number(100000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_spending_total">'.config('money.currency_code').' '.format_number(10000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance">'.config('money.currency_code').' '.format_number(90000).'</span>');
    }

    /** @test */
    public function make_sure_start_week_balance_is_calculated_from_the_previous_week_ending_balance()
    {
        $defaultBook = factory(Book::class)->create();
        $lastWeekDate = now()->subWeek()->format('Y-m-d');
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'book_id' => $defaultBook->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);
        factory(Transaction::class)->create([
            'amount' => 99000,
            'date' => $lastWeekDate,
            'book_id' => $defaultBook->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $lastWeekDate,
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);

        $this->visit('/');

        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span class="date" id="start_week_balance">'.config('money.currency_code').' '.format_number(89000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_income_total">'.config('money.currency_code').' '.format_number(100000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_spending_total">'.config('money.currency_code').' '.format_number(10000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance">'.config('money.currency_code').' '.format_number(179000).'</span>');
    }

    /** @test */
    public function make_sure_start_week_balance_is_calculated_from_the_previous_week_ending_balance_for_in_weeks_book()
    {
        Carbon::setTestNow('2024-10-16'); // This Wednesday
        $defaultBook = factory(Book::class)->create([
            'report_periode_code' => 'in_weeks',
            'start_week_day_code' => 'friday',
        ]);
        $lastWeekDate = now()->subWeek()->format('Y-m-d');
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => '2024-10-11', // Last Friday
            'book_id' => $defaultBook->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => '2024-10-12', // Last Saturday
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);
        factory(Transaction::class)->create([
            'amount' => 99000,
            'date' => '2024-10-10', // Last Thursday
            'book_id' => $defaultBook->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => '2024-10-09', // Last Wednesday
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);

        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span class="date" id="start_week_balance">'.config('money.currency_code').' '.format_number(89000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_income_total">'.config('money.currency_code').' '.format_number(100000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_spending_total">'.config('money.currency_code').' '.format_number(10000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance">'.config('money.currency_code').' '.format_number(179000).'</span>');

        Carbon::setTestNow();
    }

    /** @test */
    public function make_sure_transactions_from_next_week_are_not_calculated()
    {
        $defaultBook = factory(Book::class)->create();
        $nextWeekDate = now()->addWeek()->format('Y-m-d');
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'book_id' => $defaultBook->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);
        factory(Transaction::class)->create([
            'amount' => 99000,
            'date' => $nextWeekDate,
            'book_id' => $defaultBook->id,
            'in_out' => 0,
        ]);

        $this->visit('/');

        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span class="date" id="start_week_balance">'.config('money.currency_code').' '.format_number(0).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_income_total">'.config('money.currency_code').' '.format_number(100000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_week_spending_total">'.config('money.currency_code').' '.format_number(10000).'</span>')
            ->assertSeeHtml('<span class="date" id="current_balance">'.config('money.currency_code').' '.format_number(90000).'</span>');
    }
}
