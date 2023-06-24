<?php

namespace App\Http\Controllers\BankAccounts;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function store(BankAccount $bankAccount, Request $request)
    {
        $newBankAccountBalance = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'amount' => 'required|numeric',
            'description' => 'nullable|max:255',
        ]);
        $newBankAccountBalance['creator_id'] = auth()->id();
        $bankAccount->balances()->create($newBankAccountBalance);

        flash(__('bank_account_balance.created'), 'success');

        return redirect()->route('bank_accounts.show', $bankAccount);
    }

    public function show(BankAccount $bankAccount)
    {
        return view('bank_accounts.show', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $bankAccountData = $request->validate([
            'name' => 'required|max:60',
            'number' => 'required|max:60',
            'account_name' => 'required|max:60',
            'description' => 'nullable|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $bankAccount->update($bankAccountData);

        flash(__('bank_account.updated'), 'success');

        return redirect()->route('bank_accounts.index');
    }

    public function destroy(BankAccount $bankAccount)
    {
        request()->validate([
            'bank_account_id' => 'required',
        ]);

        if (request('bank_account_id') == $bankAccount->id && $bankAccount->delete()) {
            flash(__('bank_account.deleted'), 'success');

            return redirect()->route('bank_accounts.index');
        }

        return back();
    }
}
