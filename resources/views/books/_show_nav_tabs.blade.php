<nav class="nav nav-vertical nav-pills me-lg-2 text-uppercase">
    <a href="{{ route('books.show', [$book->id]) }}" class="nav-link {{ request('tab') == null ? 'active' : '' }}" @if (request('tab') == null) aria-current="page" @endif>
        {{ __('book.detail') }}
    </a>
    <a href="{{ route('books.show', [$book->id, 'tab' => 'signatures']) }}" class="nav-link {{ request('tab') == 'signatures' ? 'active' : '' }}" @if (request('tab') == 'signatures') aria-current="page" @endif>
        {{ __('report.signatures') }}
    </a>
    <a href="{{ route('books.show', [$book->id, 'tab' => 'landing_page']) }}" class="nav-link {{ request('tab') == 'landing_page' ? 'active' : '' }}" @if (request('tab') == 'landing_page') aria-current="page" @endif>
        {{ __('book.landing_page') }}
    </a>
</nav>
