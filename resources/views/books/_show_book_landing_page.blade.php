<div class="card">
    <div class="card-header">
        {{ __('book.landing_page') }}
        <div class="card-options">
            @can('update', $book)
                {{ link_to_route('books.edit', __('app.edit'), [$book, 'tab' => 'landing_page'], ['class' => 'btn btn-sm btn-warning text-dark mr-2', 'id' => 'edit_landing_page-book-'.$book->id]) }}
            @endcan
        </div>
    </div>
    <div class="card-body">
        {{ __('book.due_date') }}: {{ Setting::for($book)->get('due_date') }}
    </div>
    <div class="card-body">
        @if (Setting::for($book)->get('landing_page_content'))
            {!! Purify::clean(Setting::for($book)->get('landing_page_content')) !!}
        @else
            {{ __('book.landing_page_content') }}
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ __('book.poster_image') }}
            </div>
            <div class="card-body">
                @if (Setting::for($book)->get('poster_image_path'))
                    <img class="img-fluid" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}">
                @else
                    {{ __('book.poster_image') }}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                {{ __('book.thumbnail_image') }}
            </div>
            <div class="card-body">
                @if (Setting::for($book)->get('thumbnail_image_path'))
                    <img class="img-fluid" src="{{ Storage::url(Setting::for($book)->get('thumbnail_image_path')) }}" alt="{{ $book->name }}">
                @else
                    {{ __('book.thumbnail_image') }}
                @endif
            </div>
        </div>
    </div>
</div>
