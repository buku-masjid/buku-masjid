<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->format('Y'));
        $month = $request->get('month', now()->format('m'));
        $months = collect(get_months())->prepend(__('time.all_months'), '00');
        if (!$months->keys()->contains($month)) {
            $month = '00';
        }
        $book = auth()->activeBook();
        if ($month == '00') {
            $startDate = $year.'-01-01';
            $endDate = $year == now()->format('Y') ? now()->format('Y-m-d') : $year.'-12-31';
        } else {
            $yearMonth = $year.'-'.$month;
            $startDate = $yearMonth.'-01';
            $endDate = $yearMonth == now()->format('Y-m') ? now()->format('Y-m-d') : Carbon::parse($yearMonth.'-01')->format('Y-m-t');
        }

        return view('dashboard.index', compact('year', 'months', 'month', 'book', 'startDate', 'endDate'));
    }
}
