<?php

namespace App\Http\Livewire\PublicDisplay;

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
        $theme = config('public_display.theme');

        return view("livewire.public_display.themes.$theme.book_cards");
    }
}
