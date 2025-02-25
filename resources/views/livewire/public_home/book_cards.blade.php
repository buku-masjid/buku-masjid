<div>
    <div class="fs-4 pt-3 pb-3 row">
        <div class="col"><span class="fs-2 fw-bold pe-2">Program</span></div>
        <div class="col text-end"><a href="{{ route('public.donate') }}">Detil Program <i class="ti">&#xea61;</i></a></div>
    </div>
    @foreach ($publicBooks as $publicBook)
        <div class="card {{ !$loop->first ? 'mt-3' : '' }} bg-info-lt">
            <a href="{{ route('public.books.show', $publicBook) }}">
                @if (Setting::for($publicBook)->get('poster_image_path'))
                    <img src="{{ Storage::url(Setting::for($publicBook)->get('poster_image_path')) }}" class="w-100 h-100 object-cover" alt="{{ $publicBook->name }}" style="border-radius: 15px 15px;">
                @else
                    <div class="p-3 fs-1 d-flex align-items-center justify-content-center" style="min-height: 13em">{{ $publicBook->name }}</div>
                @endif
            </a>
        </div>
    @endforeach
</div>
