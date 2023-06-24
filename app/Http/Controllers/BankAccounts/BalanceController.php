<?php

namespace App\Http\Controllers\BankAccounts;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankAccountBalance;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function store(BankAccount $bankAccount, Request $request)
    {
        $this->authorize('update', $bankAccount);

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

    public function update(Request $request, BankAccount $bankAccount, BankAccountBalance $balance)
    {
        $this->authorize('update', $bankAccount);

        $bankAccountBalanceData = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'amount' => 'required|numeric',
            'description' => 'nullable|max:255',
        ]);

        $balance->update($bankAccountBalanceData);

        flash(__('bank_account_balance.updated'), 'success');

        return redirect()->route('bank_accounts.show', $bankAccount);
    }

    public function destroy(BankAccount $bankAccount, BankAccountBalance $balance)
    {
        $this->authorize('update', $bankAccount);

        request()->validate([
            'bank_account_balance_id' => 'required',
        ]);

        if (request('bank_account_balance_id') == $balance->id && $balance->delete()) {
            flash(__('bank_account_balance.deleted'), 'success');

            return redirect()->route('bank_accounts.show', $bankAccount);
        }

        return back();
    }
}
