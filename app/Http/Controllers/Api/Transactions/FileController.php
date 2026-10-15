<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\File as FileResource;
use App\Jobs\Files\OptimizeImage;
use App\Models\File;
use App\Transaction;

class FileController extends Controller
{
    /**
     * List the attachments of the given transaction.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return FileResource::collection($transaction->files()->get());
    }

    /**
     * Attach uploaded files to an existing transaction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $payload = request()->validate([
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
                'title' => $payload['title'] ?? null,
                'description' => $payload['description'] ?? null,
            ]);

            dispatch(new OptimizeImage($file));
        }

        return response()->json([
            'message' => __('file.uploaded'),
            'data' => FileResource::collection($transaction->files()->get()),
        ], 201);
    }

    /**
     * Update an attachment's title/description.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Transaction $transaction, File $file)
    {
        $this->authorize('update', $transaction);

        $attachedFile = $transaction->files()->where('id', $file->id)->first();
        if (is_null($attachedFile)) {
            abort(404);
        }

        request()->validate([
            'title' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);

        $attachedFile->update(request()->only(['title', 'description']));

        return response()->json([
            'message' => __('file.updated'),
            'data' => new FileResource($attachedFile->fresh()),
        ]);
    }

    /**
     * Remove an attachment from this transaction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transaction $transaction, File $file)
    {
        $this->authorize('update', $transaction);

        $attachedFile = $transaction->files()->where('id', $file->id)->first();
        if (is_null($attachedFile)) {
            abort(404);
        }

        request()->validate(['file_id' => ['required']]);

        if (request('file_id') != $file->id) {
            return response()->json(['message' => __('file.undeleted')]);
        }

        $attachedFile->delete();

        return response()->json(['message' => __('file.deleted')]);
    }
}
