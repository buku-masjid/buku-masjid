<?php

namespace App\Http\Livewire\PublicDisplay;

use App\Models\Book;
use Livewire\Component;

class BookCards extends Component
{
    public $publicBooks = [];
    public $theme;

    public function mount()
    {
        $this->publicBooks = Book::where('report_visibility_code', Book::REPORT_VISIBILITY_PUBLIC)
            ->get();
    }

    public function render()
    {
        $view = 'livewire.public_display.themes.default.book_cards';
        if (view()->exists("livewire.public_display.themes.$this->theme.book_cards")) {
            $view = "livewire.public_display.themes.$this->theme.book_cards";
        }

        return view($view);
    }
}
