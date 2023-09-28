<?php

namespace Tests\Feature\Liveware\PublicHome;

use App\Http\Livewire\PublicHome\WeeklyFinancialSummary;
use App\Models\Book;
use App\Transaction;
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
        $this->visit('/');
        $startOfWeekDayDate = now()->startOfWeek()->isoFormat('dddd, D MMMM Y');
        $todayDayDate = now()->isoFormat('dddd, D MMMM Y');
        Livewire::test(WeeklyFinancialSummary::class)
            ->assertSeeHtml('<span id="start_week_label">'.__('report.balance_per_date', ['date' => $startOfWeekDayDate]).'</span>')
            ->assertSeeHtml('<span id="start_week_balance">0</span>')
            ->assertSeeHtml('<span id="current_week_spending_total">0</span>')
            ->assertSeeHtml('<span id="current_balance_label">'.__('report.today_balance', ['date' => $todayDayDate]).'</span>')
            ->assertSeeHtml('<span id="current_balance">0</span>');
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
            ->assertSeeHtml('<span id="start_week_balance">0</span>')
            ->assertSeeHtml('<span id="current_week_income_total">'.number_format(100000).'</span>')
            ->assertSeeHtml('<span id="current_week_spending_total">-'.number_format(10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.number_format(90000).'</span>');
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
            ->assertSeeHtml('<span id="start_week_balance">0</span>')
            ->assertSeeHtml('<span id="current_week_income_total">'.number_format(100000).'</span>')
            ->assertSeeHtml('<span id="current_week_spending_total">-'.number_format(10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.number_format(90000).'</span>');
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
            ->assertSeeHtml('<span id="start_week_balance">'.number_format(89000).'</span>')
            ->assertSeeHtml('<span id="current_week_income_total">'.number_format(100000).'</span>')
            ->assertSeeHtml('<span id="current_week_spending_total">-'.number_format(10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.number_format(179000).'</span>');
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
            ->assertSeeHtml('<span id="start_week_balance">0</span>')
            ->assertSeeHtml('<span id="current_week_income_total">'.number_format(100000).'</span>')
            ->assertSeeHtml('<span id="current_week_spending_total">-'.number_format(10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.number_format(90000).'</span>');
    }
}
