<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateRequest;
use App\Http\Requests\Categories\DeleteRequest;
use App\Http\Requests\Categories\UpdateRequest;
use App\Models\BankAccount;
use App\Models\Category;
use App\Transaction;

class CategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new Category);

        $editableCategory = null;
        $categories = Category::orderBy('name')->with('book')->get();
        $books = $this->getBookList();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableCategory = Category::find(request('id'));
        }

        return view('categories.index', compact('categories', 'editableCategory', 'books'));
    }

    public function store(CreateRequest $categoryCreateForm)
    {
        $category = $categoryCreateForm->save();

        flash(__('category.created'), 'success');

        return redirect()->route('categories.index');
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

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
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $categories = $this->getCategoryList();
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('categories.show', compact(
            'category', 'transactions', 'year', 'incomeTotal', 'spendingTotal',
            'startDate', 'endDate', 'editableTransaction', 'categories', 'bankAccounts'
        ));
    }

    public function update(UpdateRequest $categoryUpdateForm, Category $category)
    {
        $category = $categoryUpdateForm->save();

        flash(__('category.updated'), 'success');

        return redirect()->route('categories.index');
    }

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
