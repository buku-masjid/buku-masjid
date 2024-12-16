<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Transaction;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $payload = $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:1000'],
            'title' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);

        $filePath = 'files/'.now()->format('Y/m/d');

        foreach ($payload['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $transaction->files()->create([
                'type_code' => 'image',
                'file_path' => $fileName,
                'title' => $payload['title'],
                'description' => $payload['description'],
            ]);
        }

        flash(__('file.uploaded'), 'success');

        return redirect()->route('transactions.show', $transaction);
    }

    public function destroy(Request $request, Transaction $transaction, File $file)
    {
        $this->authorize('update', $transaction);

        $file->delete();

        flash(__('file.deleted'), 'warning');

        return redirect()->route('transactions.show', $transaction);
    }
}
