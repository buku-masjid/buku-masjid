<?php

namespace App\Http\Controllers;

use App\Models\BankAccountBalance;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{
    public function inMonths(Request $request)
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
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        return view('reports.in_months', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate'
        ));
    }

    public function inMonthsPdf(Request $request)
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
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate'
        );

        // return view('reports.in_months_pdf', $passedVariables);

        $pdf = \PDF::loadView('reports.in_months_pdf', $passedVariables);

        return $pdf->stream(__('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function inOut(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        return view('reports.in_out', compact(
            'year', 'month', 'yearMonth', 'currentMonthEndDate',
            'groupedTransactions', 'incomeCategories', 'spendingCategories'
        ));
    }

    public function inOutPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'currentMonthEndDate',
            'groupedTransactions', 'incomeCategories', 'spendingCategories'
        );

        // return view('reports.in_out_pdf', $passedVariables);

        $pdf = \PDF::loadView('reports.in_out_pdf', $passedVariables);

        return $pdf->stream(__('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function inWeeks(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getWeeklyGroupedTransactions($yearMonth);
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        return view('reports.in_weeks', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'currentMonthEndDate'
        ));
    }

    public function inWeeksPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getWeeklyGroupedTransactions($yearMonth);
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));
        $passedVariables = compact('year', 'month', 'yearMonth', 'groupedTransactions', 'currentMonthEndDate');

        // return view('reports.in_weeks_pdf', $passedVariables);

        $pdf = \PDF::loadView('reports.in_weeks_pdf', $passedVariables);

        return $pdf->stream(__('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    private function getWeeklyGroupedTransactions(string $yearMonth): Collection
    {
        $lastMonthDate = Carbon::parse($yearMonth.'-01')->subDay();

        $transactions = $this->getTansactions($yearMonth);
        $groupedTransactions = collect([]);
        $lastWeekDate = null;
        foreach (get_date_range_per_week($yearMonth) as $weekNumber => $weekDates) {
            $weekTransactions = $transactions->filter(function ($transaction) use ($weekDates) {
                return in_array($transaction->date, $weekDates);
            });
            $lastWeekDate = $lastWeekDate ?: $lastMonthDate;
            if (!$weekTransactions->isEmpty()) {
                $firstBalance = new Transaction([
                    'date' => null,
                    'description' => 'Saldo per '.$lastWeekDate->isoFormat('D MMMM Y'),
                    'in_out' => 1,
                    'amount' => auth()->activeBook()->getBalance($lastWeekDate->format('Y-m-d')),
                ]);
                $firstBalance->is_strong = 1;
                $weekTransactions->prepend($firstBalance);
                $groupedTransactions->put($weekNumber, $weekTransactions->groupBy('day_name'));
                $lastWeekDate = Carbon::parse($weekTransactions->last()->date);
            }
        }

        return collect($groupedTransactions);
    }

    private function getLastBankAccountBalance(Carbon $currentMonthEndDate): BankAccountBalance
    {
        $activeBookBankAccount = auth()->activeBook()->bankAccount;
        if (is_null($activeBookBankAccount)) {
            return new BankAccountBalance([
                'date' => $currentMonthEndDate->format('Y-m-d'),
                'amount' => 0,
            ]);
        }

        $currentMonthBalance = $activeBookBankAccount->balances()
            ->where('date', '<=', $currentMonthEndDate->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->first();

        if (is_null($currentMonthBalance)) {
            return new BankAccountBalance([
                'date' => $currentMonthEndDate->format('Y-m-d'),
                'amount' => 0,
            ]);
        }

        return $currentMonthBalance;
    }
}
