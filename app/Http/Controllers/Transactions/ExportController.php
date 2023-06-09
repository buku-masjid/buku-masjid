<?php

namespace App\Http\Controllers\Transactions;

use App\Category;
use App\Http\Controllers\Controller;
use App\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function csv(Request $request)
    {
        $yearMonth = $this->getYearMonth();
        $transactions = $this->getTansactions($yearMonth);

        return Response::csv($transactions);
    }

    public function byCategory(Category $category)
    {
        $startDate = request('start_date', date('Y-m').'-01');
        $endDate = request('end_date', date('Y-m-d'));
        $transactions = $this->getCategoryTransactions($category, [
            'partner_id' => request('partner_id'),
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'query'      => request('query'),
        ]);

        return Response::csv($transactions);
    }

    public function byPartner(Partner $partner)
    {
        $startDate = request('start_date', date('Y-m').'-01');
        $endDate = request('end_date', date('Y-m-d'));
        $transactions = $this->getPartnerTransactions($partner, [
            'category_id' => request('category_id'),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'query'       => request('query'),
        ]);

        return Response::csv($transactions);
    }
}
