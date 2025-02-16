<div class="list-group list-group-transparent mb-0">
    <a href="{{ route('books.show', [$book->id]) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request('tab') == null ? 'active' : '' }}">
        {{ __('book.detail') }}
    </a>
    <a href="{{ route('books.show', [$book->id, 'tab' => 'signatures']) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request('tab') == 'signatures' ? 'active' : '' }}">
        {{ __('report.signatures') }}
    </a>
    <a href="{{ route('books.show', [$book->id, 'tab' => 'landing_page']) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request('tab') == 'landing_page' ? 'active' : '' }}">
        {{ __('book.landing_page') }}
    </a>
</div>
<br>
