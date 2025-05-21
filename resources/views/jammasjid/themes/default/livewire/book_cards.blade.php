@if ($publicBooks->isEmpty() == false)
    @foreach ($publicBooks as $publicBook)
        <div class="carousel-item">
                @if (Setting::for($publicBook)->get('poster_image_path'))
                    <img src="{{ Storage::url(Setting::for($publicBook)->get('poster_image_path')) }}">
                @else
                    <div class="p-3 fs-1 d-flex align-items-center justify-content-center" style="min-height: 320px">{{ $publicBook->name }}</div>
                @endif
        </div>
    @endforeach
@endif
