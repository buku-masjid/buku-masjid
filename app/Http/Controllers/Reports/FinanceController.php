<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\BankAccountBalance;
use App\Transaction;
use Carbon\Carbon;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FinanceController extends Controller
{
    public function summary(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();

        $startDate = Carbon::parse($yearMonth.'-01');
        $endDate = Carbon::parse($yearMonth.'-01')->endOfMonth();
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = $startDate->subDay();
        $currentMonthEndDate = $endDate;
        if ($yearMonth == Carbon::now()->format('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $reportPeriode = $book->report_periode_code;

        return view('reports.finance.'.$reportPeriode.'.summary', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate', 'reportPeriode'
        ));
    }

    public function summaryPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();

        $startDate = Carbon::parse($yearMonth.'-01');
        $endDate = Carbon::parse($yearMonth.'-01')->endOfMonth();
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = $startDate->subDay();
        $currentMonthEndDate = $endDate;
        if ($yearMonth == Carbon::now()->format('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));
        $showLetterhead = $this->showLetterhead();

        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate', 'showLetterhead', 'reportPeriode'
        );

        $reportPeriode = $book->report_periode_code;
        // return view('reports.finance.'.$reportPeriode.'.summary_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.summary_pdf', $passedVariables);

        return $pdf->stream(__('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function categorized(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $startDate = Carbon::parse($yearMonth.'-01');
        $endDate = Carbon::parse($yearMonth.'-01')->endOfMonth();
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $reportPeriode = $book->report_periode_code;

        return view('reports.finance.'.$reportPeriode.'.categorized', compact(
            'year', 'month', 'yearMonth', 'currentMonthEndDate', 'reportPeriode',
            'groupedTransactions', 'incomeCategories', 'spendingCategories'
        ));
    }

    public function categorizedPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $startDate = Carbon::parse($yearMonth.'-01');
        $endDate = Carbon::parse($yearMonth.'-01')->endOfMonth();
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $showLetterhead = $this->showLetterhead();

        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'currentMonthEndDate',
            'groupedTransactions', 'incomeCategories', 'spendingCategories',
            'showLetterhead', 'reportPeriode'
        );

        $reportPeriode = $book->report_periode_code;
        // return view('reports.finance.'.$reportPeriode.'.categorized_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.categorized_pdf', $passedVariables);

        return $pdf->stream(__('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function detailed(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();

        $startDate = Carbon::parse($yearMonth.'-01');
        $endDate = Carbon::parse($yearMonth.'-01')->endOfMonth();
        $book = auth()->activeBook();

        $groupedTransactions = $this->getWeeklyGroupedTransactions($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $currentMonthEndDate = $endDate;

        $reportPeriode = $book->report_periode_code;

        return view('reports.finance.'.$reportPeriode.'.detailed', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'currentMonthEndDate', 'reportPeriode'
        ));
    }

    public function detailedPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();

        $startDate = Carbon::parse($yearMonth.'-01');
        $endDate = Carbon::parse($yearMonth.'-01')->endOfMonth();
        $book = auth()->activeBook();

        $groupedTransactions = $this->getWeeklyGroupedTransactions($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $currentMonthEndDate = $endDate;
        $showLetterhead = $this->showLetterhead();
        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'groupedTransactions',
            'currentMonthEndDate', 'showLetterhead', 'reportPeriode'
        );

        $reportPeriode = $book->report_periode_code;
        // return view('reports.finance.'.$reportPeriode.'.detailed_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.detailed_pdf', $passedVariables);

        return $pdf->stream(__('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    private function getWeeklyGroupedTransactions(string $startDate, string $endDate): Collection
    {
        $lastMonthDate = Carbon::parse($startDate)->subDay();

        $transactions = $this->getTansactionsByDateRange($startDate, $endDate);
        $groupedTransactions = collect([]);
        $lastWeekDate = null;

        $dateRangePerWeek = get_date_range_per_week($startDate, $endDate, auth()->activeBook()->start_week_day_code);
        foreach ($dateRangePerWeek as $weekNumber => $weekDates) {
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

    // to inform the views (including css style) to show the letterhead only if masjid name and address not empty
    private function showLetterhead(): bool
    {
        return Setting::get('masjid_name', config('masjid.name')) && Setting::get('masjid_address');
    }
}
