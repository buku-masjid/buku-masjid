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
        $book = auth()->activeBook();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = Carbon::parse($yearMonth.'-01')->subDay();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));
        if ($yearMonth == date('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));

        $reportPeriode = $book->report_periode_code;

        return view('reports.finance.'.$reportPeriode.'.summary', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate'
        ));
    }

    public function summaryPdf(Request $request)
    {
        $book = auth()->activeBook();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);
        $lastMonthDate = Carbon::parse($yearMonth.'-01')->subDay();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));
        if ($yearMonth == date('Y-m')) {
            $currentMonthEndDate = Carbon::now();
        }
        $lastBankAccountBalanceOfTheMonth = $this->getLastBankAccountBalance($currentMonthEndDate);
        $lastMonthBalance = auth()->activeBook()->getBalance($lastMonthDate->format('Y-m-d'));
        $showLetterhead = $this->showLetterhead();

        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories',
            'spendingCategories', 'lastBankAccountBalanceOfTheMonth', 'lastMonthDate',
            'lastMonthBalance', 'currentMonthEndDate', 'showLetterhead'
        );

        $reportPeriode = $book->report_periode_code;
        // return view('reports.finance.'.$reportPeriode.'.summary_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.summary_pdf', $passedVariables);

        return $pdf->stream(__('report.monthly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function categorized(Request $request)
    {
        $book = auth()->activeBook();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $reportPeriode = $book->report_periode_code;

        return view('reports.finance.'.$reportPeriode.'.categorized', compact(
            'year', 'month', 'yearMonth', 'currentMonthEndDate',
            'groupedTransactions', 'incomeCategories', 'spendingCategories'
        ));
    }

    public function categorizedPdf(Request $request)
    {
        $book = auth()->activeBook();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        $showLetterhead = $this->showLetterhead();

        $passedVariables = compact(
            'year', 'month', 'yearMonth', 'currentMonthEndDate',
            'groupedTransactions', 'incomeCategories', 'spendingCategories',
            'showLetterhead'
        );

        $reportPeriode = $book->report_periode_code;
        // return view('reports.finance.'.$reportPeriode.'.categorized_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.categorized_pdf', $passedVariables);

        return $pdf->stream(__('report.categorized_transactions', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    public function detailed(Request $request)
    {
        $book = auth()->activeBook();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getWeeklyGroupedTransactions($yearMonth);
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));

        $reportPeriode = $book->report_periode_code;

        return view('reports.finance.'.$reportPeriode.'.detailed', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'currentMonthEndDate'
        ));
    }

    public function detailedPdf(Request $request)
    {
        $book = auth()->activeBook();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getWeeklyGroupedTransactions($yearMonth);
        $currentMonthEndDate = Carbon::parse(Carbon::parse($yearMonth.'-01')->format('Y-m-t'));
        $showLetterhead = $this->showLetterhead();
        $passedVariables = compact('year', 'month', 'yearMonth', 'groupedTransactions', 'currentMonthEndDate', 'showLetterhead');

        $reportPeriode = $book->report_periode_code;
        // return view('reports.finance.'.$reportPeriode.'.detailed_pdf', $passedVariables);
        $pdf = \PDF::loadView('reports.finance.'.$reportPeriode.'.detailed_pdf', $passedVariables);

        return $pdf->stream(__('report.weekly', ['year_month' => $currentMonthEndDate->isoFormat('MMMM Y')]).'.pdf');
    }

    private function getWeeklyGroupedTransactions(string $yearMonth): Collection
    {
        $lastMonthDate = Carbon::parse($yearMonth.'-01')->subDay();

        $transactions = $this->getTansactions($yearMonth);
        $groupedTransactions = collect([]);
        $lastWeekDate = null;

        $startDate = $yearMonth.'-01';
        $endDate = Carbon::parse($yearMonth.'-01')->format('Y-m-t');

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
