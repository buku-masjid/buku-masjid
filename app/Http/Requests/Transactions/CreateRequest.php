<?php

namespace App\Http\Requests\Transactions;

use App\Jobs\Files\OptimizeImage;
use App\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', new Transaction)
        && $this->user()->can('manage-transactions', auth()->activeBook());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date|date_format:Y-m-d',
            'amount' => 'required|max:60',
            'in_out' => 'required|boolean',
            'description' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'partner_id' => 'nullable|exists:partners,id',
            'book_id' => ['required', 'exists:books,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:jpg,bmp,png,avif,webp', 'max:5120'],
        ];
    }

    /**
     * Save category to the database.
     *
     * @return \App\Transaction
     */
    public function save()
    {
        $newTransaction = $this->validated();
        $newTransaction['creator_id'] = $this->user()->id;
        $transaction = Transaction::create($newTransaction);

        if (!isset($newTransaction['files'])) {
            return $transaction;
        }

        $filePath = 'files/'.now()->format('Y/m/d');
        foreach ($newTransaction['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $file = $transaction->files()->create([
                'type_code' => 'raw_image',
                'file_path' => $fileName,
            ]);
            dispatch(new OptimizeImage($file));
        }

        return $transaction;
    }
}
