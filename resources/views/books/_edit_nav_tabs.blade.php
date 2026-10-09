<nav class="nav nav-vertical nav-pills me-lg-2 text-uppercase">
    <a href="{{ route('books.edit', [$book->id]) }}" class="nav-link {{ request('tab') == null ? 'active' : '' }}" @if (request('tab') == null) aria-current="page" @endif>
        {{ __('book.detail') }}
    </a>
    <a href="{{ route('books.edit', [$book->id, 'tab' => 'signatures']) }}" class="nav-link {{ request('tab') == 'signatures' ? 'active' : '' }}" @if (request('tab') == 'signatures') aria-current="page" @endif>
        {{ __('report.signatures') }}
    </a>
    <a href="{{ route('books.edit', [$book->id, 'tab' => 'landing_page']) }}" class="nav-link {{ request('tab') == 'landing_page' ? 'active' : '' }}" @if (request('tab') == 'landing_page') aria-current="page" @endif>
        {{ __('book.landing_page') }}
    </a>
    @can('delete', $book)
    {{ link_to_route('books.edit', __('book.delete'), [$book, 'action' => 'delete'], ['class' => 'nav-link', 'id' => 'del-book-'.$book->id]) }}
    @endcan
</nav>
