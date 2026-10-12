<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicBookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class PublicBookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $books = Book::where('report_visibility_code', 'public')
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

        return PublicBookResource::collection($books);
    }
}
