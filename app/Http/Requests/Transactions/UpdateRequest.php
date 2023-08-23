<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('transaction'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'in_out' => 'required|boolean',
            'date' => 'required|date|date_format:Y-m-d',
            'amount' => 'required|max:60',
            'description' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    /**
     * Update transaction in database.
     *
     * @return \App\Models\Category
     */
    public function save()
    {
        $transaction = $this->route('transaction');
        $transaction->update($this->validated());

        return $transaction;
    }
}
