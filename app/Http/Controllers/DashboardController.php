<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->format('Y'));
        $month = $request->get('month', '00');
        $months = collect(get_months())->prepend(__('time.all_months'), '00');
        if (!$months->keys()->contains($month)) {
            $month = '00';
        }
        $book = auth()->activeBook();
        $startDate = $year.'-01-01';
        $endDate = $year == now()->format('Y') ? now()->format('Y-m-d') : $year.'-12-31';

        return view('dashboard.index', compact('year', 'months', 'month', 'book', 'startDate', 'endDate'));
    }
}
