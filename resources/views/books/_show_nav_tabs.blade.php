<div class="list-group list-group-transparent">
    <a href="{{ route('books.show', [$book->id]) }}" class="list-group-item py-2 list-group-item-action d-flex align-items-center {{ request('tab') == null ? 'active' : '' }}" @if (request('tab') == null) aria-current="page" @endif>
        {{ __('book.detail') }}
    </a>
    <a href="{{ route('books.show', [$book->id, 'tab' => 'signatures']) }}" class="list-group-item py-2 list-group-item-action d-flex align-items-center {{ request('tab') == 'signatures' ? 'active' : '' }}" @if (request('tab') == 'signatures') aria-current="page" @endif>
        {{ __('report.signatures') }}
    </a>
    <a href="{{ route('books.show', [$book->id, 'tab' => 'landing_page']) }}" class="list-group-item py-2 list-group-item-action d-flex align-items-center {{ request('tab') == 'landing_page' ? 'active' : '' }}" @if (request('tab') == 'landing_page') aria-current="page" @endif>
        {{ __('book.landing_page') }}
    </a>
</div>
