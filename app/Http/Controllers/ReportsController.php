<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Yearly transaction summary report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $partnerId = $request->get('partner_id');
        $partners = $this->getPartnerList();
        $categoryId = $request->get('category_id');
        $categories = $this->getCategoryList();
        $year = $this->getYearQuery($request->get('year'));
        $reportFormat = $request->get('format', 'in_months');

        if ($reportFormat == 'in_weeks') {
            $data = $this->getYearlyTransactionInWeeksSummary($year, auth()->id(), $partnerId, $categoryId);
            $chartData = $this->getYearlyReportInWeeksChartData($year, $data);
        } else {
            $data = $this->getYearlyTransactionSummary($year, auth()->id(), $partnerId, $categoryId);
            $chartData = $this->getYearlyReportChartData($data);
        }

        return view('reports.index', compact(
            'year', 'data', 'partners', 'partnerId', 'categories', 'categoryId', 'chartData', 'reportFormat'
        ));
    }

    /**
     * Get correct year from query string.
     *
     * @param  int|string  $yearQuery
     * @return int|string
     */
    private function getYearQuery($yearQuery)
    {
        return in_array($yearQuery, get_years()) ? $yearQuery : date('Y');
    }

    /**
     * Get transaction yearly report data.
     *
     * @param  int|string  $year
     * @param  int  $userId
     * @param  int|null  $partnerId
     * @return \Illuminate\Support\Collection
     */
    private function getYearlyTransactionSummary($year, $userId, $partnerId = null, $categoryId = null)
    {
        $rawQuery = 'MONTH(date) as month';
        $rawQuery .= ', YEAR(date) as year';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportQuery = DB::table('transactions')->select(DB::raw($rawQuery))
            ->where(DB::raw('YEAR(date)'), $year)
            ->where('creator_id', $userId);

        if ($partnerId) {
            $reportQuery->where('partner_id', $partnerId);
        }

        if ($categoryId) {
            $reportQuery->where('category_id', $categoryId);
        }

        $reportsData = $reportQuery->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        $reports = [];
        foreach ($reportsData as $report) {
            $key = str_pad($report->month, 2, '0', STR_PAD_LEFT);
            $reports[$key] = $report;
            $reports[$key]->difference = $report->income - $report->spending;
        }

        return collect($reports);
    }

    public function getYearlyReportChartData($reportData)
    {
        $defaultMonthValues = collect(get_months())->map(function ($item, $key) {
            return [
                'month' => month_id($key),
                'income' => 0,
                'spending' => 0,
                'difference' => 0,
            ];
        });

        $chartData = $reportData->map(function ($item) {
            return [
                'month' => month_id($item->month),
                'income' => $item->income,
                'spending' => $item->spending,
                'difference' => $item->difference,
            ];
        });

        return $defaultMonthValues->replace($chartData)->values();
    }

    private function getYearlyTransactionInWeeksSummary($year, $userId, $partnerId = null, $categoryId = null)
    {
        $rawQuery = 'WEEK(date, 1) as week';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportQuery = DB::table('transactions')->select(DB::raw($rawQuery))
            ->where(DB::raw('YEAR(date)'), $year)
            ->where('creator_id', $userId);

        if ($partnerId) {
            $reportQuery->where('partner_id', $partnerId);
        }

        if ($categoryId) {
            $reportQuery->where('category_id', $categoryId);
        }

        $reportsData = $reportQuery->orderBy('date', 'ASC')
            ->groupBy(DB::raw('WEEK(date, 1)'))
            ->get();

        $reports = [];
        foreach ($reportsData as $report) {
            $key = $report->week;
            $reports[$key] = $report;
            $reports[$key]->difference = $report->income - $report->spending;
        }

        return collect($reports);
    }

    public function getYearlyReportInWeeksChartData($year, $reportData)
    {
        $defaultMonthValues = collect(get_week_numbers($year))->map(function ($item, $key) {
            return [
                'week' => $key,
                'income' => 0,
                'spending' => 0,
                'difference' => 0,
            ];
        });

        $chartData = $reportData->map(function ($item) {
            return [
                'week' => $item->week,
                'income' => $item->income,
                'spending' => $item->spending,
                'difference' => $item->difference,
            ];
        });

        return $defaultMonthValues->replace($chartData)->values();
    }
}
