<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) request('year', date('Y'));
        $book = auth()->activeBook();

        return view('dashboard.index', compact('year', 'book'));
    }
}
