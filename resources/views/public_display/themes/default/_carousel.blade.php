<div id="carousel-default" class="carousel slide w-full" data-bs-ride="carousel">
    <div class="carousel-inner h-100">
        <div class="carousel-item active">
            @if (Setting::get('masjid_photo_path'))
                <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}">
            @else
                <div style="background-color: #f8f8f8; height: 360px"></div>
            @endif
        </div>
        @livewire('public-display.book-cards')
    </div>
</div>
