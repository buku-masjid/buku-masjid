<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index()
    {
        $editableBook = null;
        $bookQuery = Book::orderBy('name');
        $books = $bookQuery->with('creator')->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableBook = Book::find(request('id'));
        }

        return view('books.index', compact('books', 'editableBook'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Book);

        $newBook = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newBook['creator_id'] = auth()->id();

        Book::create($newBook);

        return redirect()->route('books.index');
    }

    public function show(Book $book)
    {
        $books = [];
        $editableTransaction = null;
        $year = request('year', date('Y'));
        $categories = $this->getCategoryList()->prepend('-- '.__('transaction.no_category').' --', 'null');

        $defaultStartDate = auth()->user()->account_start_date ?: date('Y-m').'-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-d'));

        $transactions = $this->getBookTransactions($book, [
            'category_id' => request('category_id'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'query' => request('query'),
        ]);
        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $books = $this->getBookList();
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('books.show', compact(
            'book', 'transactions', 'year', 'incomeTotal', 'spendingTotal',
            'startDate', 'endDate', 'categories', 'editableTransaction', 'books'
        ));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $bookData = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
            'status_id' => ['required', Rule::in(Book::getConstants('STATUS'))],
        ]);
        $book->update($bookData);

        return redirect()->route('books.index');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        request()->validate([
            'book_id' => 'required',
        ]);

        if (request('book_id') == $book->id && $book->delete()) {
            return redirect()->route('books.index');
        }

        return back();
    }
}
