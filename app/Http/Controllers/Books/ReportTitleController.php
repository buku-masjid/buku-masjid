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
        $redirectRoute = 'reports.in_months';
        if ($request->has('report_titles.in_out')) {
            $redirectRoute = 'reports.in_out';
        }
        if ($request->has('report_titles.in_weeks')) {
            $redirectRoute = 'reports.in_weeks';
        }
        if ($request->has('reset_report_title.in_out')) {
            $bookData['report_titles']['in_out'] = null;
        }
        if ($request->has('reset_report_title.in_weeks')) {
            $bookData['report_titles']['in_weeks'] = null;
        }
        if ($request->has('reset_report_title.in_months')) {
            $bookData['report_titles']['in_months'] = null;
        }
        $book->update(['report_titles' => $bookData['report_titles']]);

        flash(__('report.title_updated'), 'success');

        return redirect()->route($redirectRoute);
    }
}
