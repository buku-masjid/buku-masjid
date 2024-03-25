<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class ReportTitleController extends Controller
{
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $bookData = $request->validate([
            'report_titles' => ['required', 'array'],
            'report_titles.*' => ['string', 'max:100'],
            'book_id' => ['required', 'in:'.$book->id],
            'nonce' => ['required', 'in:'.$book->nonce],
        ]);
        $redirectRoute = $this->getRedirectRouteFromRequest($request);
        $reportTitles = $this->getReportTitlesFromRequest($request);
        $currentBookReportTitles = $book->report_titles ?: [];
        $reportTitles = array_merge($currentBookReportTitles, $reportTitles);
        $book->update(['report_titles' => $reportTitles]);

        flash(__('report.title_updated'), 'success');

        return redirect()->route($redirectRoute, $request->except([
            'report_titles', 'action', '_method', '_token',
            'book_id', 'nonce', 'reset_report_title',
        ]));
    }

    private function getRedirectRouteFromRequest(Request $request)
    {
        $redirectRoute = 'reports.finance.summary';
        if ($request->has('report_titles.finance_categorized')) {
            $redirectRoute = 'reports.finance.categorized';
        }
        if ($request->has('report_titles.finance_detailed')) {
            $redirectRoute = 'reports.finance.detailed';
        }

        return $redirectRoute;
    }

    private function getReportTitlesFromRequest(Request $request)
    {
        $reportTitles = $request->get('report_titles');

        if ($request->has('reset_report_title.finance_summary')) {
            $reportTitles['finance_summary'] = null;
        }
        if ($request->has('reset_report_title.finance_categorized')) {
            $reportTitles['finance_categorized'] = null;
        }
        if ($request->has('reset_report_title.finance_detailed')) {
            $reportTitles['finance_detailed'] = null;
        }

        return $reportTitles;
    }
}
