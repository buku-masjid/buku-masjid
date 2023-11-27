<?php

namespace Tests\Feature\Livewire\Books;

use App\Http\Livewire\Books\FinancialSummary;
use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InWeeksWithoutBudgetFinancialSummaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_book_financial_summary_card_without_budget()
    {
        $book = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS, 'budget' => null]);
        $lastPeriodeDate = now()->startOfWeek()->subDay()->isoFormat('dddd, D MMM Y');
        $todayDayDate = now()->isoFormat('dddd, D MMM Y');

        Livewire::test(FinancialSummary::class, ['bookId' => $book->id])
            ->assertSeeHtml('<span id="start_periode_label">'.__('report.balance_per_date', ['date' => $lastPeriodeDate]).'</span>')
            ->assertSeeHtml('<span id="start_periode_balance">'.format_number(0).'</span>')
            ->assertSeeHtml('<span id="current_periode_spending_total">'.format_number(0).'</span>')
            ->assertSeeHtml('<span id="current_balance_label">'.__('report.today_balance', ['date' => $todayDayDate]).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.format_number(0).'</span>');
    }

    /** @test */
    public function user_can_see_book_financial_summary_card_without_budget()
    {
        $book = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS, 'budget' => null]);
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

        Livewire::test(FinancialSummary::class, ['bookId' => $book->id])
            ->assertSeeHtml('<span id="start_periode_balance">'.format_number(0).'</span>')
            ->assertSeeHtml('<span id="current_periode_income_total">'.format_number(100000).'</span>')
            ->assertSeeHtml('<span id="current_periode_spending_total">'.format_number(-10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.format_number(90000).'</span>');
    }

    /** @test */
    public function make_sure_other_books_transactions_are_not_calculated()
    {
        $book = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS, 'budget' => null]);
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'book_id' => $book->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'book_id' => $book->id,
            'in_out' => 0,
        ]);
        $anotherBook = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS, 'budget' => null]);
        factory(Transaction::class)->create([
            'amount' => 35000,
            'date' => $today,
            'book_id' => $anotherBook->id,
            'in_out' => 1,
        ]);

        Livewire::test(FinancialSummary::class, ['bookId' => $book->id])
            ->assertSeeHtml('<span id="start_periode_balance">'.format_number(0).'</span>')
            ->assertSeeHtml('<span id="current_periode_income_total">'.format_number(100000).'</span>')
            ->assertSeeHtml('<span id="current_periode_spending_total">'.format_number(-10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.format_number(90000).'</span>');
    }

    /** @test */
    public function make_sure_start_periode_balance_is_calculated_from_the_previous_periode_ending_balance()
    {
        $book = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS, 'budget' => null]);
        $lastPeriodeDate = now()->subWeek()->subDays(2)->format('Y-m-d');
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 99000,
            'date' => $lastPeriodeDate,
            'book_id' => $book->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $lastPeriodeDate,
            'book_id' => $book->id,
            'in_out' => 0,
        ]);
        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'book_id' => $book->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'book_id' => $book->id,
            'in_out' => 0,
        ]);

        Livewire::test(FinancialSummary::class, ['bookId' => $book->id])
            ->assertSeeHtml('<span id="start_periode_balance">'.format_number(89000).'</span>')
            ->assertSeeHtml('<span id="current_periode_income_total">'.format_number(100000).'</span>')
            ->assertSeeHtml('<span id="current_periode_spending_total">'.format_number(-10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.format_number(179000).'</span>');
    }

    /** @test */
    public function make_sure_transactions_from_next_periode_are_not_calculated()
    {
        $book = factory(Book::class)->create(['report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS, 'budget' => null]);
        $nextWeekDate = now()->addWeek()->format('Y-m-d');
        $today = today()->format('Y-m-d');

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => $today,
            'book_id' => $book->id,
            'in_out' => 1,
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => $today,
            'book_id' => $book->id,
            'in_out' => 0,
        ]);
        factory(Transaction::class)->create([
            'amount' => 99000,
            'date' => $nextWeekDate,
            'book_id' => $book->id,
            'in_out' => 0,
        ]);

        Livewire::test(FinancialSummary::class, ['bookId' => $book->id])
            ->assertSeeHtml('<span id="start_periode_balance">'.format_number(0).'</span>')
            ->assertSeeHtml('<span id="current_periode_income_total">'.format_number(100000).'</span>')
            ->assertSeeHtml('<span id="current_periode_spending_total">'.format_number(-10000).'</span>')
            ->assertSeeHtml('<span id="current_balance">'.format_number(90000).'</span>');
    }
}
