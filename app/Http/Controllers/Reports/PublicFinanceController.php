<?php

namespace App\Http\Controllers\Reports;

use App\Models\BankAccountBalance;
use App\Models\Book;
use Carbon\Carbon;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PublicFinanceController extends FinanceController
{
    public function index(Request $request)
    {
        $book = auth()->activeBook();
        if ($book->report_visibility_code != 'public') {
            return redirect()->to('/');
        }

        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);

        $groupedTransactions = $this->getWeeklyGroupedTransactions($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))
            ->sortKeysDesc();
        $weekLabels = $this->getWeekLabelsByDateRange(
            $startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $book->start_week_day_code
        );
        $transactionsByInOut = $groupedTransactions->flatten()->groupBy('in_out');
        $currentMonthIncome = $transactionsByInOut->has(1) ? $transactionsByInOut[1]->sum('amount') : 0;
        $currentMonthSpending = $transactionsByInOut->has(0) ? $transactionsByInOut[0]->sum('amount') : 0;
        $currentMonthBalance = $currentMonthIncome - $currentMonthSpending;
        $lastMonthDate = $startDate->clone()->subDay();
        $currentMonthEndDate = $endDate->clone();
        if ($startDate->format('Y-m') == Carbon::now()->format('Y-m') && $book->report_periode_code == 'in_months') {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $reportPeriode = $book->report_periode_code;
        $selectedBook = $book;
        $selectedMonth = $startDate->format('m');
        $showBudgetSummary = $this->determineBudgetSummaryVisibility($request, $book);
        $books = Book::where('status_id', Book::STATUS_ACTIVE)
            ->where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get();
        $isTransactionFilesVisible = Setting::for($book)->get('transaction_files_visibility_code') == Book::REPORT_VISIBILITY_PUBLIC;

        return view('public_reports.finance.index', compact(
            'startDate', 'endDate', 'groupedTransactions', 'books', 'selectedBook', 'selectedMonth', 'currentMonthIncome',
            'lastBankAccountBalanceOfTheMonth', 'lastMonthDate', 'currentMonthSpending', 'currentMonthBalance',
            'lastMonthBalance', 'currentMonthEndDate', 'reportPeriode', 'showBudgetSummary', 'isTransactionFilesVisible',
            'weekLabels'
        ));
    }

    public function summary(Request $request)
    {
        $book = auth()->activeBook();
        if ($book->report_visibility_code != 'public') {
            return redirect()->route('public_reports.index');
        }

        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $transactionsByInOut = $groupedTransactions->flatten()->groupBy('in_out');
        $currentMonthIncome = $transactionsByInOut->has(1) ? $transactionsByInOut[1]->sum('amount') : 0;
        $currentMonthSpending = $transactionsByInOut->has(0) ? $transactionsByInOut[0]->sum('amount') : 0;
        $currentMonthBalance = $currentMonthIncome - $currentMonthSpending;

        $lastMonthDate = $startDate->clone()->subDay();
        $currentMonthEndDate = $endDate->clone();
        if ($startDate->format('Y-m') == Carbon::now()->format('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $reportPeriode = $book->report_periode_code;
        $showBudgetSummary = $this->determineBudgetSummaryVisibility($request, $book);
        $selectedBook = $book;
        $books = Book::where('status_id', Book::STATUS_ACTIVE)
            ->where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get();

        return view('public_reports.finance.'.$reportPeriode.'.summary', compact(
            'startDate', 'endDate', 'groupedTransactions', 'incomeCategories', 'selectedBook', 'books', 'spendingCategories',
            'lastBankAccountBalanceOfTheMonth', 'lastMonthDate', 'lastMonthBalance', 'currentMonthEndDate', 'reportPeriode',
            'showBudgetSummary', 'currentMonthIncome', 'currentMonthSpending', 'currentMonthBalance'
        ));
    }

    public function categorized(Request $request)
    {
        $book = auth()->activeBook();
        if ($book->report_visibility_code != 'public') {
            return redirect()->route('public_reports.index');
        }

        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);

        $groupedTransactions = $this->getTansactionsByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $transactionsByInOut = $groupedTransactions->flatten()->groupBy('in_out');
        $currentMonthIncome = $transactionsByInOut->has(1) ? $transactionsByInOut[1]->sum('amount') : 0;
        $currentMonthSpending = $transactionsByInOut->has(0) ? $transactionsByInOut[0]->sum('amount') : 0;
        $currentMonthBalance = $currentMonthIncome - $currentMonthSpending;
        $lastMonthDate = $startDate->clone()->subDay();
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $currentMonthEndDate = $endDate->clone();

        $reportPeriode = $book->report_periode_code;
        $isTransactionFilesVisible = Setting::for($book)->get('transaction_files_visibility_code') == Book::REPORT_VISIBILITY_PUBLIC;
        $selectedBook = $book;
        $books = Book::where('status_id', Book::STATUS_ACTIVE)
            ->where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get();

        return view('public_reports.finance.'.$reportPeriode.'.categorized', compact(
            'startDate', 'endDate', 'currentMonthEndDate', 'reportPeriode', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'isTransactionFilesVisible', 'selectedBook', 'books', 'currentMonthIncome',
            'currentMonthSpending', 'currentMonthBalance', 'lastMonthBalance'
        ));
    }

    public function detailed(Request $request)
    {
        $book = auth()->activeBook();
        if ($book->report_visibility_code != 'public') {
            return redirect()->route('public_reports.index');
        }

        $startDate = $this->getStartDate($request);
        $endDate = $this->getEndDate($request);

        $groupedTransactions = $this->getWeeklyGroupedTransactionsForDetail($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $transactionsByInOut = $groupedTransactions->flatten()->groupBy('in_out');
        $currentMonthIncome = $transactionsByInOut->has(1) ? $transactionsByInOut[1]->sum('amount') : 0;
        $currentMonthSpending = $transactionsByInOut->has(0) ? $transactionsByInOut[0]->sum('amount') : 0;
        $currentMonthBalance = $currentMonthIncome - $currentMonthSpending;
        $lastMonthDate = $startDate->clone()->subDay();
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));
        $currentMonthEndDate = $endDate->clone();
        $weekLabels = $this->getWeekLabelsByDateRange(
            $startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $book->start_week_day_code
        );

        $reportPeriode = $book->report_periode_code;
        $lastMonthDate = Carbon::parse($startDate)->subDay();
        $isTransactionFilesVisible = Setting::for($book)->get('transaction_files_visibility_code') == Book::REPORT_VISIBILITY_PUBLIC;
        $selectedBook = $book;
        $books = Book::where('status_id', Book::STATUS_ACTIVE)
            ->where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get();

        return view('public_reports.finance.'.$reportPeriode.'.detailed', compact(
            'startDate', 'endDate', 'groupedTransactions', 'currentMonthEndDate', 'reportPeriode', 'lastMonthDate',
            'isTransactionFilesVisible', 'selectedBook', 'books', 'weekLabels', 'currentMonthIncome', 'currentMonthSpending',
            'currentMonthBalance', 'lastMonthBalance'
        ));
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
                $groupedTransactions->put($weekNumber, $weekTransactions->groupBy('category_id')->sortKeys());
                $lastWeekDate = Carbon::parse($weekTransactions->last()->date);
            }
        }

        return collect($groupedTransactions);
    }

    private function getWeeklyGroupedTransactionsForDetail(string $startDate, string $endDate): Collection
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
