<ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="true">
            <span class="ms-2 d-lg-block">
                <span class="text-default">{{ auth()->activeBook()->name }}</span>
                <small class="text-body-secondary d-block">
                    {{ config('money.currency_code') }} {{ format_number(auth()->activeBook()->getBalance(date('Y-m-d'))) }}
                </small>
            </span>
        </a>
        <div class="dropdown-menu" data-bs-popper="static">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    {{ Form::open(['route' => 'book_switcher.store']) }}
                    @foreach ($activeBooks as $bookId => $bookName)
                        <button type="submit" class="dropdown-item" name="switch_book" value="{{ $bookId }}" id="switch_book_{{ $bookId }}">
                            <i class="dropdown-icon ti {{ auth()->activeBookId() == $bookId ? 'ti-book' : 'ti-book-2' }}"></i>&nbsp;
                            {{ $bookName }}
                        </button>
                    @endforeach
                    {{ Form::close() }}

                    @can('view-any', new App\Models\Book)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('books.index') }}">
                            <i class="dropdown-icon ti ti-book-2"></i> &nbsp;{{ __('book.all') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </li>
</ul>
