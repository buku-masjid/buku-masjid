<?php

namespace App\Http\Requests\Categories;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('category'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:60',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string|max:255',
            'status_id' => ['required', Rule::in(Category::getConstants('STATUS'))],
            'book_id' => 'required|exists:books,id',
            'report_visibility_code' => ['required', Rule::in(Category::getConstants('REPORT_VISIBILITY'))],
        ];
    }

    /**
     * Update category in database.
     *
     * @return \App\Models\Category
     */
    public function save()
    {
        $category = $this->route('category');
        $category->update($this->validated());

        return $category;
    }
}
