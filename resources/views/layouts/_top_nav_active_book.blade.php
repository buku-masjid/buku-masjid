<div class="d-flex order-lg-0">
    <div class="dropdown">
        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
            <span class="ml-2 d-lg-block">
                <span class="text-default">{{ auth()->activeBook()->name }}</span>
                <small class="text-muted d-block mt-1">
                    {{ config('masjid.currency_code') }} {{ number_format(auth()->activeBook()->getBalance(date('Y-m-d')), 0) }}
                </small>
            </span>
        </a>
        @desktop
        <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow mt-3">
        @elsedesktop
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow mt-3">
        @enddesktop
            {{ Form::open(['route' => 'book_switcher.store']) }}
            @foreach ($activeBooks as $bookId => $bookName)
                <button type="submit" class="dropdown-item" name="switch_book" value="{{ $bookId }}" id="switch_book_{{ $bookId }}">
                    <i class="dropdown-icon fe {{ auth()->activeBookId() == $bookId ? 'fe-book-open' : 'fe-book' }}"></i>
                    {{ $bookName }}
                </button>
            @endforeach
            {{ Form::close() }}

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('books.index') }}">
                <i class="dropdown-icon fe fe-book"></i> {{ __('book.all') }}
            </a>
        </div>
    </div>
</div>
