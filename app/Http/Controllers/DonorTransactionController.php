<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Http\Request;

class DonorTransactionController extends Controller
{
    public function create()
    {
        $partners = $this->getAvailablePartners(['donatur']);
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');

        $books = Book::orderBy('name')
            ->where('status_id', Book::STATUS_ACTIVE)
            ->pluck('name', 'id');

        return view('donors.transactions.create', compact('partners', 'bankAccounts', 'books'));
    }

    public function store(Request $request)
    {
        $newTransaction = $request->validate([
            'date' => 'required|date|date_format:Y-m-d',
            'amount' => 'required|max:60',
            'notes' => 'nullable|max:255',
            'partner_id' => 'required|exists:partners,id',
            'book_id' => ['required', 'exists:books,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
        ]);
        $partner = Partner::find($newTransaction['partner_id']);
        $newTransaction['description'] = __('donor.donation_from', ['donor_name' => $partner->name]).'|'.$newTransaction['notes'];
        $newTransaction['in_out'] = Transaction::TYPE_INCOME;
        $newTransaction['creator_id'] = auth()->id();
        $transaction = Transaction::create($newTransaction);

        flash(__('transaction.income_added'), 'success');

        return redirect()->route('donors.index');
    }

    private function getAvailablePartners(array $partnerTypeCodes): array
    {
        $partners = Partner::where('is_active', Partner::STATUS_ACTIVE)
            ->where('type_code', 'donatur')
            ->orderBy('name')
            ->get();

        return $partners->pluck('name', 'id')->toArray();
    }
}
