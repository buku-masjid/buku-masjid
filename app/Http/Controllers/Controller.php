<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Lecturing;
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
        $bookId = request('book_id');
        $bankAccountId = request('bank_account_id');

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

        $transactionQuery->when($bookId, function ($queryBuilder, $bookId) {
            if ($bookId == 'null') {
                $queryBuilder->whereNull('book_id');
            } else {
                $queryBuilder->where('book_id', $bookId);
            }
        });

        $transactionQuery->when($bankAccountId, function ($queryBuilder, $bankAccountId) {
            if ($bankAccountId == 'null') {
                $queryBuilder->whereNull('bank_account_id');
            } else {
                $queryBuilder->where('bank_account_id', $bankAccountId);
            }
        });

        return $transactionQuery->orderBy('date', 'asc')
            ->with('category', 'bankAccount', 'book')
            ->withCount('files')
            ->get();
    }

    protected function getTansactionsByDateRange(string $startDate, string $endDate)
    {
        $categoryId = request('category_id');
        $bookId = request('book_id');
        $bankAccountId = request('bank_account_id');

        $transactionQuery = Transaction::query();
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        $transactionQuery->where('description', 'like', '%'.request('query').'%');

        $transactionQuery->when($categoryId, function ($queryBuilder, $categoryId) {
            if ($categoryId == 'null') {
                $queryBuilder->whereNull('category_id');
            } else {
                $queryBuilder->where('category_id', $categoryId);
            }
        });

        $transactionQuery->when($bookId, function ($queryBuilder, $bookId) {
            if ($bookId == 'null') {
                $queryBuilder->whereNull('book_id');
            } else {
                $queryBuilder->where('book_id', $bookId);
            }
        });

        $transactionQuery->when($bankAccountId, function ($queryBuilder, $bankAccountId) {
            if ($bankAccountId == 'null') {
                $queryBuilder->whereNull('bank_account_id');
            } else {
                $queryBuilder->where('bank_account_id', $bankAccountId);
            }
        });

        return $transactionQuery->orderBy('date', 'asc')->with('category', 'book', 'files')->get();
    }

    protected function getIncomeTotal($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : 0;
        });
    }

    protected function getSpendingTotal($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? 0 : $transaction->amount;
        });
    }

    protected function getBookList()
    {
        return Book::orderBy('name')->where('status_id', Book::STATUS_ACTIVE)->pluck('name', 'id');
    }

    protected function getCategoryList()
    {
        return Category::orderBy('name')->where('status_id', Category::STATUS_ACTIVE)->pluck('name', 'id');
    }

    protected function getCategoryTransactions(Category $category, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];

        $transactionQuery = $category->transactions();
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        $transactionQuery->when($query, function ($queryBuilder, $query) {
            $queryBuilder->where('description', 'like', '%'.$query.'%');
        });

        return $transactionQuery->orderBy('date', 'desc')->with('book')->get();
    }

    protected function getBookTransactions(Book $book, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];
        $categoryId = $criteria['category_id'];

        $transactionQuery = $book->transactions();

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

    protected function getAudienceCodeList(): array
    {
        return [
            Lecturing::AUDIENCE_FRIDAY => __('lecturing.audience_'.Lecturing::AUDIENCE_FRIDAY),
            Lecturing::AUDIENCE_PUBLIC => __('lecturing.audience_'.Lecturing::AUDIENCE_PUBLIC),
            Lecturing::AUDIENCE_MUSLIMAH => __('lecturing.audience_'.Lecturing::AUDIENCE_MUSLIMAH),
            Lecturing::AUDIENCE_TARAWIH => __('lecturing.audience_'.Lecturing::AUDIENCE_TARAWIH),
        ];
    }
}
