@livewire('public-books.financial-summary', ['bookId' => $book->id])
<a
    href="{{ route('public_reports.index', ['active_book_id' => $book->id, 'nonce' => $book->nonce]) }}"
    class="btn bm-btn btn-outline-cyan mt-3"
    id="show-book-{{ $book->id }}">
    {{ __('app.show') }}
</a>
