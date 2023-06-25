<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        $groupedTransactions = $this->getTansactions($yearMonth)->groupBy('in_out');
        $incomeCategories = isset($groupedTransactions[1]) ? $groupedTransactions[1]->pluck('category')->unique()->filter() : collect([]);
        $spendingCategories = isset($groupedTransactions[0]) ? $groupedTransactions[0]->pluck('category')->unique()->filter() : collect([]);

        return view('reports.index', compact(
            'year', 'month', 'yearMonth', 'groupedTransactions', 'incomeCategories', 'spendingCategories'
        ));
    }
}
