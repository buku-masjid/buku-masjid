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
            ->get();
        $this->publicBooks->each(function ($book) {
            if ($book->budget > 0) {
                $book->income_total = $book->transactions()->withoutGlobalScope('forActiveBook')->where('in_out', 1)->sum('amount');
                $book->progressPercent = get_percent($book->income_total, (float) $book->budget);
                $book->progressPercentColor = $this->getProgressPercentColor($book->progressPercent);
            }
        });
    }

    private function getProgressPercentColor(float $progressPercent): string
    {
        if ($progressPercent > 75) {
            return 'success';
        }
        if ($progressPercent > 50) {
            return 'info';
        }
        if ($progressPercent > 25) {
            return 'warning';
        }

        return 'danger';
    }

    public function render()
    {
        return view('livewire.public_home.book_cards');
    }
}
