<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Partner;
use App\User;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new Book);

        $editableBook = null;
        $bookQuery = Book::orderBy('name');
        $books = $bookQuery->with('creator', 'bankAccount')->paginate(25);
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableBook = Book::find(request('id'));
        }

        return view('books.index', compact('books', 'editableBook', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Book);

        $newBook = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
            'budget' => ['nullable', 'numeric'],
            'bank_account_id' => 'nullable|exists:bank_accounts,id',
        ]);
        $newBook['creator_id'] = auth()->id();

        Book::create($newBook);
        flash(__('book.created'), 'success');

        return redirect()->route('books.index');
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);
        $currentBalance = 0;
        $startBalance = 0;
        $currentIncomeTotal = 0;
        $currentSpendingTotal = 0;

        $currentTransactions = $book->transactions()
            ->withoutGlobalScope('forActiveBook')
            ->get();
        $currentIncomeTotal = $currentTransactions->where('in_out', 1)->sum('amount');
        $currentSpendingTotal = $currentTransactions->where('in_out', 0)->sum('amount');
        $endOfLastDate = today()->startOfWeek()->subDay()->format('Y-m-d');
        $startBalance = $book->getBalance($endOfLastDate);
        $currentBalance = $startBalance + $currentIncomeTotal - $currentSpendingTotal;

        return view('books.show', compact(
            'book', 'startBalance', 'currentBalance', 'currentIncomeTotal', 'currentSpendingTotal'
        ));
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');
        $partnerTypes = (new Partner)->getAvailableTypes();
        $financeUsers = User::where('role_id', User::ROLE_FINANCE)->pluck('name', 'id');

        return view('books.edit', compact('book', 'bankAccounts', 'financeUsers', 'partnerTypes'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $partnerTypes = collect((new Partner)->getAvailableTypes())->keys()->implode(',');

        $bookData = $request->validate([
            'name' => ['sometimes', 'max:60'],
            'description' => ['nullable', 'max:255'],
            'status_id' => ['sometimes', Rule::in(Book::getConstants('STATUS'))],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'report_visibility_code' => ['sometimes', Rule::in(Book::getConstants('REPORT_VISIBILITY'))],
            'transaction_files_visibility_code' => ['sometimes', Rule::in(Book::getConstants('REPORT_VISIBILITY'))],
            'budget' => ['nullable', 'numeric'],
            'report_periode_code' => ['sometimes', Rule::in(Book::getConstants('REPORT_PERIODE'))],
            'income_partner_codes' => ['nullable', 'array'],
            'income_partner_codes.*' => ['in:'.$partnerTypes],
            'income_partner_null' => ['nullable', 'string', 'max:20'],
            'spending_partner_codes' => ['nullable', 'array'],
            'spending_partner_codes.*' => ['in:'.$partnerTypes],
            'spending_partner_null' => ['nullable', 'string', 'max:20'],
            'has_pdf_page_number' => ['nullable', 'boolean'],
            'start_week_day_code' => ['sometimes', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'management_title' => ['nullable', 'string', 'max:60'],
            'acknowledgment_text_left' => ['nullable', 'string', 'max:20'],
            'sign_position_left' => ['nullable', 'string', 'max:20'],
            'sign_name_left' => ['nullable', 'string', 'max:60'],
            'acknowledgment_text_mid' => ['nullable', 'string', 'max:20'],
            'sign_position_mid' => ['nullable', 'string', 'max:20'],
            'sign_name_mid' => ['nullable', 'string', 'max:60'],
            'acknowledgment_text_right' => ['nullable', 'string', 'max:20'],
            'sign_position_right' => ['nullable', 'string', 'max:20'],
            'sign_name_right' => ['nullable', 'string', 'max:60'],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
            'landing_page_content' => ['nullable', 'string', 'max:10000'],
        ]);
        if ($request->user()->cannot('change-manager', $book)) {
            unset($bookData['manager_id']);
        }
        $book->update($bookData);
        $this->updateBookSettings($book, $bookData);
        flash(__('book.updated'), 'success');

        $routeParams = [$book];

        if (array_key_exists('landing_page_content', $bookData)) {
            $routeParams = [$book, 'tab' => 'landing_page'];
        }

        if (array_key_exists('acknowledgment_text_left', $bookData)) {
            $routeParams = [$book, 'tab' => 'signatures'];
        }

        return redirect()->route('books.show', $routeParams);
    }

    private function updateBookSettings(Book $book, array $bookData): void
    {
        array_key_exists('management_title', $bookData) ? Setting::for($book)->set('management_title', $bookData['management_title']) : null;
        array_key_exists('acknowledgment_text_left', $bookData) ? Setting::for($book)->set('acknowledgment_text_left', $bookData['acknowledgment_text_left']) : null;
        array_key_exists('sign_position_left', $bookData) ? Setting::for($book)->set('sign_position_left', $bookData['sign_position_left']) : null;
        array_key_exists('sign_name_left', $bookData) ? Setting::for($book)->set('sign_name_left', $bookData['sign_name_left']) : null;
        array_key_exists('acknowledgment_text_mid', $bookData) ? Setting::for($book)->set('acknowledgment_text_mid', $bookData['acknowledgment_text_mid']) : null;
        array_key_exists('sign_position_mid', $bookData) ? Setting::for($book)->set('sign_position_mid', $bookData['sign_position_mid']) : null;
        array_key_exists('sign_name_mid', $bookData) ? Setting::for($book)->set('sign_name_mid', $bookData['sign_name_mid']) : null;
        array_key_exists('acknowledgment_text_right', $bookData) ? Setting::for($book)->set('acknowledgment_text_right', $bookData['acknowledgment_text_right']) : null;
        array_key_exists('sign_position_right', $bookData) ? Setting::for($book)->set('sign_position_right', $bookData['sign_position_right']) : null;
        array_key_exists('sign_name_right', $bookData) ? Setting::for($book)->set('sign_name_right', $bookData['sign_name_right']) : null;
        array_key_exists('transaction_files_visibility_code', $bookData) ? Setting::for($book)->set('transaction_files_visibility_code', $bookData['transaction_files_visibility_code']) : null;
        array_key_exists('has_pdf_page_number', $bookData) ? Setting::for($book)->set('has_pdf_page_number', $bookData['has_pdf_page_number']) : null;
        array_key_exists('income_partner_codes', $bookData) ? Setting::for($book)->set('income_partner_codes', json_encode(array_keys($bookData['income_partner_codes'] ?? []))) : null;
        array_key_exists('income_partner_null', $bookData) ? Setting::for($book)->set('income_partner_null', $bookData['income_partner_null']) : null;
        array_key_exists('spending_partner_codes', $bookData) ? Setting::for($book)->set('spending_partner_codes', json_encode(array_keys($bookData['spending_partner_codes'] ?? []))) : null;
        array_key_exists('spending_partner_null', $bookData) ? Setting::for($book)->set('spending_partner_null', $bookData['spending_partner_null']) : null;
        array_key_exists('due_date', $bookData) ? Setting::for($book)->set('due_date', $bookData['due_date']) : null;
        array_key_exists('landing_page_content', $bookData) ? Setting::for($book)->set('landing_page_content', $bookData['landing_page_content']) : null;
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        request()->validate([
            'book_id' => 'required',
        ]);

        DB::beginTransaction();
        $book->categories()->delete();
        $book->transactions()->delete();
        $isBookDeleted = $book->delete();
        DB::commit();

        if (request('book_id') == $book->id && $isBookDeleted) {
            flash(__('book.deleted'), 'warning');

            return redirect()->route('books.index');
        }

        return back();
    }
}
