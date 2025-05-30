<?php

namespace App\Http\Controllers\Reports;

use App\Models\BankAccount;
use App\Models\BankAccountBalance;
use Carbon\Carbon;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InternalFinanceController extends FinanceController
{
    public function dashboard(Request $request)
    {
        $year = (int) $request->get('year', now()->format('Y'));
        $month = $request->get('month', now()->format('m'));
        $months = collect(get_months())->prepend(__('time.all_months'), '00');
        if (!$months->keys()->contains($month)) {
            $month = '00';
        }
        $book = auth()->activeBook();
        $reportPeriode = $book->report_periode_code;
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);

        return view('reports.finance.'.$reportPeriode.'.dashboard', compact(
            'year', 'months', 'month', 'book', 'startDate', 'endDate'
        ));
    }

    public function dashboardPdf(Request $request)
    {
        $year = (int) $request->get('year', now()->format('Y'));
        $month = $request->get('month', now()->format('m'));
        $months = collect(get_months())->prepend(__('time.all_months'), '00');
        if (!$months->keys()->contains($month)) {
            $month = '00';
        }
        $book = auth()->activeBook();
        $reportPeriode = $book->report_periode_code;
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $showLetterhead = $this->showLetterhead();

        $passedVariables = compact(
            'year', 'months', 'month', 'book', 'startDate', 'endDate', 'showLetterhead'
        );

        // return view('reports.finance.'.$reportPeriode.'.dashboard_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.dashboard_pdf', $passedVariables);

        return $pdf->stream(__('dashboard.dashboard').'.pdf');
    }

    public function summary(Request $request)
    {
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = $startDate->clone()->subDay();
        $currentMonthEndDate = $endDate->clone();
        if ($startDate->format('Y-m') == Carbon::now()->format('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $reportPeriode = $book->report_periode_code;
        $showBudgetSummary = $this->determineBudgetSummaryVisibility($request, $book);
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id')
            ->prepend(__('transaction.cash'), 'null');

        return view('reports.finance.'.$reportPeriode.'.summary', compact(
            'startDate', 'endDate', 'groupedTransactions', 'incomeCategories', 'bankAccounts',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate', 'reportPeriode', 'showBudgetSummary'
        ));
    }

    public function summaryPdf(Request $request)
    {
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = $startDate->clone()->subDay();
        $currentMonthEndDate = $endDate->clone();
        if ($startDate->format('Y-m') == Carbon::now()->format('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));
        $showLetterhead = $this->showLetterhead();

        $reportPeriode = $book->report_periode_code;
        $showBudgetSummary = $this->determineBudgetSummaryVisibility($request, $book);

        $passedVariables = compact(
            'startDate', 'endDate', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate', 'showLetterhead', 'reportPeriode', 'showBudgetSummary'
        );

        // return view('reports.finance.'.$reportPeriode.'.summary_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.summary_pdf', $passedVariables);

        return $pdf->stream(__('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function categorized(Request $request)
    {
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $currentMonthEndDate = $endDate->clone();

        $reportPeriode = $book->report_periode_code;
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id')
            ->prepend(__('transaction.cash'), 'null');

        return view('reports.finance.'.$reportPeriode.'.categorized', compact(
            'startDate', 'endDate', 'currentMonthEndDate', 'reportPeriode', 'bankAccounts',
            'groupedTransactions', 'incomeCategories', 'spendingCategories'
        ));
    }

    public function categorizedPdf(Request $request)
    {
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $book = auth()->activeBook();

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $currentMonthEndDate = $endDate->clone();

        $showLetterhead = $this->showLetterhead();

        $reportPeriode = $book->report_periode_code;
        $passedVariables = compact(
            'startDate', 'endDate', 'currentMonthEndDate',
            'groupedTransactions', 'incomeCategories', 'spendingCategories',
            'showLetterhead', 'reportPeriode'
        );

        // return view('reports.finance.'.$reportPeriode.'.categorized_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.categorized_pdf', $passedVariables);

        return $pdf->stream(__('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function detailed(Request $request)
    {
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $book = auth()->activeBook();

        $groupedTransactions = $this->getWeeklyGroupedTransactions($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $currentMonthEndDate = $endDate->clone();
        $weekLabels = $this->getWeekLabelsByDateRange(
            $startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $book->start_week_day_code
        );

        $reportPeriode = $book->report_periode_code;
        $lastMonthDate = Carbon::parse($startDate)->subDay();
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id')
            ->prepend(__('transaction.cash'), 'null');

        return view('reports.finance.'.$reportPeriode.'.detailed', compact(
            'startDate', 'endDate', 'groupedTransactions', 'currentMonthEndDate', 'reportPeriode', 'lastMonthDate',
            'bankAccounts', 'weekLabels'
        ));
    }

    public function detailedPdf(Request $request)
    {
        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);
        $book = auth()->activeBook();

        $groupedTransactions = $this->getWeeklyGroupedTransactions($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $currentMonthEndDate = $endDate->clone();
        $weekLabels = $this->getWeekLabelsByDateRange(
            $startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $book->start_week_day_code
        );
        $showLetterhead = $this->showLetterhead();
        $reportPeriode = $book->report_periode_code;
        $lastMonthDate = Carbon::parse($startDate)->subDay();
        $passedVariables = compact(
            'startDate', 'endDate', 'groupedTransactions', 'lastMonthDate', 'currentMonthEndDate', 'showLetterhead',
            'reportPeriode', 'weekLabels'
        );

        // return view('reports.finance.'.$reportPeriode.'.detailed_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.detailed_pdf', $passedVariables);

        return $pdf->stream(__('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    private function getWeeklyGroupedTransactions(string $startDate, string $endDate): Collection
    {
        $transactions = $this->getTansactionsByDateRange($startDate, $endDate);
        $groupedTransactions = collect([]);
        $dateRangePerWeek = get_date_range_per_week($startDate, $endDate, auth()->activeBook()->start_week_day_code);
        foreach ($dateRangePerWeek as $weekNumber => $weekDates) {
            $weekTransactions = $transactions->filter(function ($transaction) use ($weekDates) {
                return in_array($transaction->date, $weekDates);
            });
            if (!$weekTransactions->isEmpty()) {
                $groupedTransactions->put($weekNumber, $weekTransactions->groupBy('day_name'));
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
