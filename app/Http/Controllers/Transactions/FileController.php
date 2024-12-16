<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $payload = $request->validate([
            'file' => ['required', 'file', 'max:3096'],
            'description' => ['nullable', 'max:255'],
        ]);

        $filePath = 'files/'.now()->format('Y/m/d');
        $fileName = $payload['file']->store($filePath);

        $transaction->files()->create([
            'file_path' => $fileName,
            'description' => $payload['description'],
            'title' => __('transaction.transaction').' '.$transaction->id,
        ]);

        return redirect()->route('transactions.show', $transaction);
    }
}
