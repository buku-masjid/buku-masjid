<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) request('year', now()->format('Y'));
        $book = auth()->activeBook();
        $startDate = $year.'-01-01';
        $endDate = $year == now()->format('Y') ? now()->format('Y-m-d') : $year.'-12-31';

        return view('dashboard.index', compact('year', 'book', 'startDate', 'endDate'));
    }
}
