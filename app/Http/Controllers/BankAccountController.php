<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $editableBankAccount = null;
        $bankAccounts = BankAccount::paginate();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableBankAccount = BankAccount::find(request('id'));
        }

        return view('bank_accounts.index', compact('bankAccounts', 'editableBankAccount'));
    }

    public function store(Request $request)
    {
        $newBankAccount = $request->validate([
            'name' => 'required|max:60',
            'number' => 'required|max:60',
            'account_name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newBankAccount['creator_id'] = auth()->id();

        BankAccount::create($newBankAccount);

        flash(__('bank_account.created'), 'success');

        return redirect()->route('bank_accounts.index');
    }

    public function show(BankAccount $bankAccount)
    {
        $editableBankAccountBalance = null;
        if (in_array(request('action'), ['edit_bank_account_balance', 'delete_bank_account_balance']) && request('bank_account_balance_id')) {
            $editableBankAccountBalance = $bankAccount->balances()->where('id', request('bank_account_balance_id'))->first();
        }
        $bankAccountBalances = $bankAccount->balances()->orderBy('date', 'desc')->with('creator')->get();

        return view('bank_accounts.show', compact('bankAccount', 'editableBankAccountBalance', 'bankAccountBalances'));
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
