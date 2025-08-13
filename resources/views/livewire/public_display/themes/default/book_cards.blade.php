@if ($publicBooks->isEmpty() == false)
    @foreach ($publicBooks as $publicBook)
        <div class="carousel-slide flex-shrink-0 w-full h-full">
                @if (Setting::for($publicBook)->get('poster_image_path'))
                    <img src="{{ Storage::url(Setting::for($publicBook)->get('poster_image_path')) }}" class="w-full h-full object-cover rounded-2xl">
                @else
                    <div class="w-full h-full object-cover rounded-2xl">{{ $publicBook->name }}</div>
                @endif
        </div>
    @endforeach
@endif
