<?php

namespace App\Http\Livewire\Donors;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class BooksCount extends Component
{
    public $booksCount;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.donors.books_count');
    }

    public function getBooksCount()
    {
        $this->booksCount = $this->calculateBooksCount();
        $this->isLoading = false;
    }

    private function calculateBooksCount()
    {
        $cacheKey = 'calculateBooksCount';
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $amount = Book::count();

        Cache::put($cacheKey, $amount, $duration);

        return $amount;
    }
}
