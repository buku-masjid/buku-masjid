<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateRequest;
use App\Http\Requests\Categories\DeleteRequest;
use App\Http\Requests\Categories\UpdateRequest;
use App\Models\Category;
use App\Transaction;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $editableCategory = null;
        $categories = Category::orderBy('name')->with('book')->get();
        $books = $this->getBookList();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableCategory = Category::find(request('id'));
        }

        return view('categories.index', compact('categories', 'editableCategory', 'books'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $categoryCreateForm)
    {
        $category = $categoryCreateForm->save();

        flash(__('category.created'), 'success');

        return redirect()->route('categories.index');
    }

    /**
     * Show transaction listing of a category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        $categories = [];
        $editableTransaction = null;
        $year = request('year', date('Y'));

        $defaultStartDate = date('Y-m').'-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-d'));

        $transactions = $this->getCategoryTransactions($category, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'query' => request('query'),
        ]);
        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $categories = $this->getCategoryList();
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('categories.show', compact(
            'category', 'transactions', 'year', 'incomeTotal', 'spendingTotal',
            'startDate', 'endDate', 'editableTransaction', 'categories'
        ));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \App\Http\Requests\Categories\UpdateRequest  $categoryUpdateForm
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Routing\Redirector
     */
    public function update(UpdateRequest $categoryUpdateForm, Category $category)
    {
        $category = $categoryUpdateForm->save();

        flash(__('category.updated'), 'success');

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Http\Requests\Categories\DeleteRequest  $categoryDeleteForm
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(DeleteRequest $categoryDeleteForm, Category $category)
    {
        if ($categoryDeleteForm->delete()) {
            flash(__('category.deleted'), 'warning');

            return redirect()->route('categories.index');
        }

        flash(__('category.undeleted'), 'warning');

        return back();
    }
}
