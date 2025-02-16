<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Book);

        $newBook = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newBook['creator_id'] = auth()->id();

        $book = Book::create($newBook);

        return response()->json([
            'message' => __('book.created'),
            'data' => $book,
        ], 201);
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $bookData = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $book->update($bookData);

        return response()->json([
            'message' => __('book.updated'),
            'data' => $book,
        ]);
    }

    public function destroy(Request $request, Book $book)
    {
        $this->authorize('delete', $book);

        $bookData = $request->validate(['book_id' => 'required']);

        if ($bookData['book_id'] == $book->id && $book->delete()) {
            return response()->json(['message' => __('book.deleted')]);
        }

        return response()->json('Unprocessable Entity.', 422);
    }

    public function updatePosterImage(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validatedPayload = $request->validate([
            'image' => 'required',
        ]);

        if (!base64_decode($validatedPayload['image'])) {
            return response()->json([
                'message' => __('masjid_profile.image_not_found'),
            ]);
        }

        if ($bookPosterPath = Setting::for($book)->get('poster_image_path')) {
            Storage::delete($bookPosterPath);
        }

        $imageParts = explode(';base64,', $validatedPayload['image']);
        $imageBase64 = base64_decode($imageParts[1]);
        $imageName = uniqid().'.webp';

        Storage::put($imageName, $imageBase64);
        Setting::for($book)->set('poster_image_path', $imageName);

        return response()->json([
            'message' => __('book.poster_image_updated'),
            'image' => Storage::url($imageName),
        ]);
    }

    public function updateThumbnailImage(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validatedPayload = $request->validate([
            'image' => 'required',
        ]);

        if (!base64_decode($validatedPayload['image'])) {
            return response()->json([
                'message' => __('masjid_profile.image_not_found'),
            ]);
        }

        if ($bookThumbnailPath = Setting::for($book)->get('thumbnail_image_path')) {
            Storage::delete($bookThumbnailPath);
        }

        $imageParts = explode(';base64,', $validatedPayload['image']);
        $imageBase64 = base64_decode($imageParts[1]);
        $imageName = uniqid().'.webp';

        Storage::put($imageName, $imageBase64);
        Setting::for($book)->set('thumbnail_image_path', $imageName);

        return response()->json([
            'message' => __('book.thumbnail_image_updated'),
            'image' => Storage::url($imageName),
        ]);
    }
}
