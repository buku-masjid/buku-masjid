@if (Setting::for($book)->get('poster_image_path'))
    <img class="img-fluid" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}">
    <hr>
@endif
@if (Setting::for($book)->get('landing_page_content'))
    {!! Purify::clean(Setting::for($book)->get('landing_page_content')) !!}
@else
    {{ $book->description }}
@endif
