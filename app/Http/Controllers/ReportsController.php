<?php

namespace App\Http\Controllers;

use App\Models\BankAccountBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = Carbon::parse($yearMonth.'-01')->subDay();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = balance($lastMonthDate->format('Y-m-d'));

        return view('reports.index', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate'
        ));
    }

    private function getLastBankAccountBalance(Carbon $currentMonthEndDate): BankAccountBalance
    {
        $currentMonthBalance = BankAccountBalance::where('date', '<=', $currentMonthEndDate->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->first();
        if ($currentMonthBalance) {
            return $currentMonthBalance;
        }

        return new BankAccountBalance([
            'date' => $currentMonthEndDate->format('Y-m-d'),
            'amount' => 0,
        ]);
    }
}
