<div class="list-group list-group-transparent">
    <a href="{{ route('books.edit', [$book->id]) }}" class="list-group-item py-2 list-group-item-action d-flex align-items-center {{ request('tab') == null ? 'active' : '' }}" @if (request('tab') == null) aria-current="page" @endif>
        {{ __('book.detail') }}
    </a>
    <a href="{{ route('books.edit', [$book->id, 'tab' => 'signatures']) }}" class="list-group-item py-2 list-group-item-action d-flex align-items-center {{ request('tab') == 'signatures' ? 'active' : '' }}" @if (request('tab') == 'signatures') aria-current="page" @endif>
        {{ __('report.signatures') }}
    </a>
    <a href="{{ route('books.edit', [$book->id, 'tab' => 'landing_page']) }}" class="list-group-item py-2 list-group-item-action d-flex align-items-center {{ request('tab') == 'landing_page' ? 'active' : '' }}" @if (request('tab') == 'landing_page') aria-current="page" @endif>
        {{ __('book.landing_page') }}
    </a>
    @can('delete', $book)
    {{ link_to_route('books.edit', __('book.delete'), [$book, 'action' => 'delete'], ['class' => 'list-group-item py-2 list-group-item-action d-flex align-items-center', 'id' => 'del-book-'.$book->id]) }}
    @endcan
</div>
