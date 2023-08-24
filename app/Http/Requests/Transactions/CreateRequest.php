<?php

namespace App\Http\Requests\Transactions;

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
        return $this->user()->can('create', new Transaction);
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
            'book_id' => ['required', 'exists:books,id'],
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

        return Transaction::create($newTransaction);
    }
}
