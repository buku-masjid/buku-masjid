<?php

namespace Tests\Feature\Liveware\PublicHome;

use App\Http\Livewire\PublicHome\TodayFinancialReports;
use App\Models\Book;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TodayFinancialReportsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_empty_today_financial_report_card()
    {
        factory(Book::class)->create();
        $this->visit('/');
        Livewire::test(TodayFinancialReports::class)
            ->assertSee(0);
    }

    /** @test */
    public function user_can_see_today_financial_report_card()
    {
        factory(Book::class)->create();

        factory(Transaction::class)->create([
            'amount' => 100000,
            'date' => date('Y-m-d'),
            'in_out' => 1
        ]);
        factory(Transaction::class)->create([
            'amount' => 10000,
            'date' => date('Y-m-d'),
            'in_out' => 0
        ]);

        $this->visit('/');
        Livewire::test(TodayFinancialReports::class)
            ->assertSee(number_format(90000))
            ->assertSee(number_format(10000))
            ->assertSee(number_format(100000));
    }
}
