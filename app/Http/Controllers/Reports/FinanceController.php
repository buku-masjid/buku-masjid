<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    protected function determineBudgetSummaryVisibility(Request $request, Book $book): bool
    {
        if (!$book->budget) {
            return false;
        }

        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_MONTHS) {
            $startDate = $this->getStartDate($request)->format('Y-m-d');
            $endDate = $this->getEndDate($request)->format('Y-m-d');
            $expextedStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $expextedEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');

            return $expextedStartDate == $startDate && $expextedEndDate == $endDate;
        }

        if ($book->report_periode_code == Book::REPORT_PERIODE_IN_WEEKS) {
            $startDate = $this->getStartDate($request)->format('Y-m-d');
            $endDate = $this->getEndDate($request)->format('Y-m-d');
            $startDayInteger = constant('\Carbon\Carbon::'.strtoupper($book->start_week_day_code));
            $expextedStartDate = Carbon::now()->startOfWeek($startDayInteger)->format('Y-m-d');
            $endDayInteger = constant('\Carbon\Carbon::'.strtoupper($book->start_week_day_code));
            $expextedEndDate = Carbon::now()->endOfWeek($endDayInteger)->subDay()->format('Y-m-d');

            return $expextedStartDate == $startDate && $expextedEndDate == $endDate;
        }

        if ($book->report_periode_code == Book::REPORT_PERIODE_ALL_TIME) {
            return true;
        }

        return false;
    }

    protected function getStartDate(Request $request): Carbon
    {
        $book = auth()->activeBook();
        if (in_array($book->report_periode_code, ['all_time'])) {
            if ($request->has('start_date')) {
                return Carbon::parse($request->get('start_date'));
            } else {
                $firstTransaction = $book->transactions()->first();
                if (is_null($firstTransaction)) {
                    return Carbon::now()->subDays(30);
                }

                return Carbon::parse($firstTransaction->date);
            }
        }
        if (in_array($book->report_periode_code, ['in_weeks'])) {
            if ($request->has('start_date')) {
                return Carbon::parse($request->get('start_date'));
            } else {
                $startDayInteger = constant('\Carbon\Carbon::'.strtoupper($book->start_week_day_code));

                return Carbon::now()->startOfWeek($startDayInteger);
            }
        }

        if ($request->has('year') && $request->has('month')) {
            $year = (int) $request->get('year');
            $month = $request->get('month');
            if ($month == '00') {
                return Carbon::parse($year.'-01-01');
            }
            if (!isset(get_months()[$month])) {
                $month = Carbon::now()->format('m');
            }

            return Carbon::parse($year.'-'.$month.'-01');
        }

        $yearMonth = $this->getYearMonth();

        return Carbon::parse($yearMonth.'-01');
    }

    protected function getEndDate(Request $request): Carbon
    {
        $book = auth()->activeBook();
        if (in_array($book->report_periode_code, ['in_weeks'])) {
            if ($request->has('end_date')) {
                return Carbon::parse($request->get('end_date'));
            } else {
                $endDayInteger = constant('\Carbon\Carbon::'.strtoupper($book->start_week_day_code));

                return Carbon::now()->endOfWeek($endDayInteger)->subDay();
            }
        }
        if ($request->has('end_date')) {
            return Carbon::parse($request->get('end_date'));
        }

        if ($request->has('year') && $request->has('month')) {
            $year = (int) $request->get('year');
            $month = $request->get('month');
            if ($month == '00') {
                if ($year == Carbon::now()->format('Y')) {
                    return Carbon::now();
                }

                return Carbon::parse($year.'-12-31');
            }
            if (!isset(get_months()[$month])) {
                $month = Carbon::now()->format('m');
            }

            if ($year.'-'.$month == Carbon::now()->format('Y-m')) {
                return now();
            }

            return Carbon::parse(Carbon::parse($year.'-'.$month.'-10')->format('Y-m-t'));
        }

        $yearMonth = $this->getYearMonth();

        if ($yearMonth == Carbon::now()->format('Y-m')) {
            return now();
        }

        return Carbon::parse($yearMonth.'-01')->endOfMonth();
    }

    protected function getWeekLabelsByDateRange(string $startDate, string $endDate, string $startWeekDay)
    {
        $dateRangePerWeek = get_date_range_per_week($startDate, $endDate, $startWeekDay);

        $dateRanges = [];
        foreach ($dateRangePerWeek as $weekNumber => $weekDateRanges) {
            $dateRangeText = get_date_range_text($weekDateRanges[0], end($weekDateRanges));
            $dateRanges[$weekNumber] = $dateRangeText;
        }

        return $dateRanges;
    }
}
