<h4 class="page-title mb-3">&nbsp;</h4>

<div class="list-group list-group-transparent mb-0">
    <a href="{{ route('books.edit', [$book->id]) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request('tab') == null ? 'active' : '' }}">
        {{ __('settings.settings') }}
    </a>
    <a href="{{ route('books.edit', [$book->id, 'tab' => 'landing_page']) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request('tab') == 'landing_page' ? 'active' : '' }}">
        {{ __('book.landing_page') }}
    </a>
</div>
<br>
@can('delete', $book)
{{ link_to_route('books.edit', __('book.delete'), [$book, 'action' => 'delete'], ['class' => 'btn btn-danger', 'id' => 'del-book-'.$book->id]) }}
@endcan
