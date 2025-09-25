<?php

namespace App\Http\Requests\Transactions;

use App\Models\Category;
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
        return $this->user()->can('update', $this->route('transaction'))
        && $this->user()->can('manage-transactions', $this->route('transaction')->book);
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
            'category_id' => ['nullable', 'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $category = Category::find($value);
                        if ($category) {
                            $inOut = $this->input('in_out');
                            $expectedColor = $inOut ? config('masjid.income_color') : config('masjid.spending_color');
                            if ($category->color !== $expectedColor) {
                                $transactionType = $inOut ? __('transaction.income') : __('transaction.spending');
                                $fail(__('validation.category_type_mismatch', [
                                    'category' => $category->name,
                                    'type' => $transactionType
                                ]));
                            }
                        }
                    }
                },
            ],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
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
