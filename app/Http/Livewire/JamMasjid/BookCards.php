<?php

namespace App\Http\Livewire\JamMasjid;

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
        $theme = env('JAMMASJID_THEME', 'default');
        
        return view("jammasjid.themes.$theme.livewire.book_cards");
    }
}
