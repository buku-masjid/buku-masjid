<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CreateRequest;
use App\Http\Requests\Categories\DeleteRequest;
use App\Http\Requests\Categories\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Get a listing of the category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return $categories;
    }

    /**
     * Get categories based on transaction type (income/spending).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionCategories(Request $request)
    {
        $request->validate([
            'in_out' => 'required|in:0,1',
            'book_id' => 'required|exists:books,id',
        ]);

        $categoryColor = $request->in_out ? config('masjid.income_color') : config('masjid.spending_color');
        
        $categories = Category::where('status_id', Category::STATUS_ACTIVE)
            ->where('book_id', $request->book_id)
            ->where('color', $categoryColor)
            ->orderBy('name')
            ->get(['id', 'name', 'color']);

        return response()->json([
            'data' => $categories,
        ]);
    }

    /**
     * Store a newly created category in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $categoryCreateForm)
    {
        $category = $categoryCreateForm->save();

        return response()->json([
            'message' => __('category.created'),
            'data' => $category->fresh(),
        ], 201);
    }

    /**
     * Update the specified category in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $categoryUpdateForm, Category $category)
    {
        $category = $categoryUpdateForm->save();

        return response()->json([
            'message' => __('category.updated'),
            'data' => $category,
        ]);
    }

    /**
     * Remove the specified category from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $categoryDeleteForm, Category $category)
    {
        if ($categoryDeleteForm->delete()) {
            return response()->json(['message' => __('category.deleted')]);
        }

        return response()->json('Unprocessable Entity.', 422);
    }
}
