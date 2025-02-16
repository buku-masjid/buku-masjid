@livewire('books.financial-summary', ['bookId' => $book->id])

<a
    href="{{ route('public_reports.index', ['active_book_id' => $book->id, 'nonce' => $book->nonce]) }}"
    class="btn btn-success float-end mt-3"
    id="show-book-{{ $book->id }}">
    {{ __('app.show') }}
</a>
