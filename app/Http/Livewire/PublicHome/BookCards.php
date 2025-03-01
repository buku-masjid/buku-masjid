<?php

namespace App\Http\Livewire\PublicHome;

use App\Models\Book;
use Livewire\Component;

class BookCards extends Component
{
    public $publicBooks = [];

    public function mount()
    {
        $this->publicBooks = Book::where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->where('report_periode_code', Book::REPORT_PERIODE_ALL_TIME)
            ->get();
    }

    public function render()
    {
        return view('livewire.public_home.book_cards');
    }
}
