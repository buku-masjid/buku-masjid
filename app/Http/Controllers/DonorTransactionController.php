<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DonorTransactionController extends Controller
{
    public function create()
    {
        $partners = $this->getAvailablePartners(['donatur']);
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $books = Book::orderBy('name')
            ->where('status_id', Book::STATUS_ACTIVE)
            ->pluck('name', 'id');

        return view('donors.transactions.create', compact('partners', 'bankAccounts', 'genders', 'books'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'date' => 'required|date|date_format:Y-m-d',
            'amount' => 'required|max:60',
            'notes' => 'nullable|max:255',
            'partner_id' => 'required_without:partner_name',
            'partner_name' => 'required_without:partner_id|max:60',
            'partner_phone' => 'required_without:partner_id|max:255',
            'partner_gender_code' => 'required_without:partner_id|in:m,f',
            'book_id' => ['required', 'exists:books,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
        ]);
        $partner = null;
        $partnerName = $payload['partner_name'] ?? null;
        if ($payload['partner_id']) {
            $partner = Partner::find($payload['partner_id']);
            $partnerName = $partner->name;
        }
        $transactionDescription = __('donor.donation_from', ['donor_name' => $partnerName]);
        if ($payload['notes']) {
            $transactionDescription .= '|'.$payload['notes'];
        }

        if (strlen($transactionDescription) > 255) {
            $descriptionPrefix = str_replace('|'.$payload['notes'], '', $transactionDescription);
            $maxNotesLength = (255 - strlen($descriptionPrefix));
            throw ValidationException::withMessages([
                'notes' => [__('validation.donor.notes.max', ['max' => $maxNotesLength])],
            ]);
        }

        if (!$payload['partner_id']) {
            $partner = Partner::create([
                'name' => $payload['partner_name'],
                'phone' => $payload['partner_phone'],
                'gender_code' => $payload['partner_gender_code'],
                'type_code' => 'donatur',
                'creator_id' => auth()->id(),
            ]);
        }

        $newTransaction = [
            'date' => $payload['date'],
            'amount' => $payload['amount'],
            'partner_id' => $partner->id,
            'book_id' => $payload['book_id'],
            'bank_account_id' => $payload['bank_account_id'],
            'in_out' => Transaction::TYPE_INCOME,
            'creator_id' => auth()->id(),
        ];
        $newTransaction['description'] = $transactionDescription;
        $transaction = Transaction::create($newTransaction);

        flash(__('transaction.income_added'), 'success');

        if ($referencePage = $request->get('reference_page')) {
            if ($referencePage == 'donor') {
                return redirect()->route('donors.show', [$partner->id]);
            }
        }

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
