<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) request('year', date('Y'));
        $month = request('month', date('m'));
        if (!isset(get_months()[$month])) {
            $month = date('m');
        }
        $book = auth()->activeBook();

        return view('dashboard.index', compact('year', 'month', 'book'));
    }
}
