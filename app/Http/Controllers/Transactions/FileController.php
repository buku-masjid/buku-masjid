<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Jobs\Files\OptimizeImage;
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
            'files.*' => ['file', 'mimes:jpg,bmp,png,avif,webp', 'max:5120'],
            'title' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);

        $filePath = 'files/'.now()->format('Y/m/d');

        foreach ($payload['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $file = $transaction->files()->create([
                'type_code' => 'raw_image',
                'file_path' => $fileName,
                'title' => $payload['title'],
                'description' => $payload['description'],
            ]);

            dispatch(new OptimizeImage($file));
        }

        flash(__('file.uploaded'), 'success');

        return redirect()->route('transactions.show', $transaction);
    }

    public function update(Request $request, Transaction $transaction, File $file)
    {
        $this->authorize('update', $transaction);

        $payload = $request->validate([
            'title' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);

        $file->update([
            'title' => $payload['title'],
            'description' => $payload['description'],
        ]);

        flash(__('file.updated'), 'success');

        return redirect()->route('transactions.show', $transaction);
    }

    public function destroy(Request $request, Transaction $transaction, File $file)
    {
        $this->authorize('update', $transaction);

        $deletableFile = $transaction->files()->where('id', $file->id)->first();
        if (is_null($deletableFile)) {
            abort(404);
        }
        $deletableFile->delete();

        flash(__('file.deleted'), 'warning');

        return redirect()->route('transactions.show', $transaction);
    }
}
