<?php

namespace App\Http\Controllers;

use App\Category;
use App\Partner;
use App\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getYearMonth()
    {
        $date = request('date');
        $year = request('year', date('Y'));
        $month = request('month', date('m'));
        $yearMonth = $year.'-'.$month;

        $explodedYearMonth = explode('-', $yearMonth);

        if (count($explodedYearMonth) == 2 && checkdate($explodedYearMonth[1], '01', $explodedYearMonth[0])) {
            if (checkdate($explodedYearMonth[1], $date, $explodedYearMonth[0])) {
                return $explodedYearMonth[0].'-'.$explodedYearMonth[1].'-'.$date;
            }

            return $explodedYearMonth[0].'-'.$explodedYearMonth[1];
        }

        return date('Y-m');
    }

    protected function getTansactions($yearMonth)
    {
        $categoryId = request('category_id');
        $partnerId = request('partner_id');

        $transactionQuery = Transaction::query();
        $transactionQuery->where('date', 'like', $yearMonth.'%');
        $transactionQuery->where('description', 'like', '%'.request('query').'%');

        $transactionQuery->when($categoryId, function ($queryBuilder, $categoryId) {
            if ($categoryId == 'null') {
                $queryBuilder->whereNull('category_id');
            } else {
                $queryBuilder->where('category_id', $categoryId);
            }
        });

        $transactionQuery->when($partnerId, function ($queryBuilder, $partnerId) {
            if ($partnerId == 'null') {
                $queryBuilder->whereNull('partner_id');
            } else {
                $queryBuilder->where('partner_id', $partnerId);
            }
        });

        return $transactionQuery->orderBy('date', 'desc')->with('category', 'partner', 'loan')->get();
    }

    /**
     * Get income total of a transaction listing.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $transactions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getIncomeTotal($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : 0;
        });
    }

    /**
     * Get spending total of a transaction listing.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $transactions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSpendingTotal($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? 0 : $transaction->amount;
        });
    }

    /**
     * Get partner list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPartnerList()
    {
        return Partner::orderBy('name')->where('status_id', Partner::STATUS_ACTIVE)->pluck('name', 'id');
    }

    /**
     * Get category list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getCategoryList()
    {
        return Category::orderBy('name')->where('status_id', Category::STATUS_ACTIVE)->pluck('name', 'id');
    }

    /**
     * Get transaction listing of a category.
     *
     * @param  \App\Category  $category
     * @param  array  $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getCategoryTransactions(Category $category, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];
        $partnerId = $criteria['partner_id'];

        $transactionQuery = $category->transactions();
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        $transactionQuery->when($query, function ($queryBuilder, $query) {
            $queryBuilder->where('description', 'like', '%'.$query.'%');
        });
        $transactionQuery->when($partnerId, function ($queryBuilder, $partnerId) {
            if ($partnerId == 'null') {
                $queryBuilder->whereNull('partner_id');
            } else {
                $queryBuilder->where('partner_id', $partnerId);
            }
        });

        return $transactionQuery->orderBy('date', 'desc')->with('partner')->get();
    }

    /**
     * Get transaction listing of a partner.
     *
     * @param  \App\Partner  $partner
     * @param  array  $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPartnerTransactions(Partner $partner, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];
        $categoryId = $criteria['category_id'];

        $transactionQuery = $partner->transactions();

        $transactionQuery->when($query, function ($queryBuilder, $query) {
            $queryBuilder->where('description', 'like', '%'.$query.'%');
        });

        $transactionQuery->whereBetween('date', [$startDate, $endDate]);

        $transactionQuery->when($categoryId, function ($queryBuilder, $categoryId) {
            if ($categoryId == 'null') {
                $queryBuilder->whereNull('category_id');
            } else {
                $queryBuilder->where('category_id', $categoryId);
            }
        });

        return $transactionQuery->orderBy('date', 'desc')->with('category')->get();
    }
}
