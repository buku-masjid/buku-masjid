<?php

namespace App\Http\Requests\Categories;

use App\Models\Category;
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
        return $this->user()->can('create', new Category);
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
            'book_id' => 'required|exists:books,id',
        ];
    }

    /**
     * Save category to the database.
     *
     * @return \App\Models\Category
     */
    public function save()
    {
        $newCategory = $this->validated();
        $newCategory['creator_id'] = $this->user()->id;

        return Category::create($newCategory);
    }
}
