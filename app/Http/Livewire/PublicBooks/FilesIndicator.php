<?php

namespace App\Http\Livewire\PublicBooks;

use Livewire\Component;

class FilesIndicator extends Component
{
    public $transaction;
    public $files;

    public function mount()
    {
        $this->files = $this->transaction->files;
    }

    public function render()
    {
        return view('livewire.public_books.files_indicator');
    }
}
