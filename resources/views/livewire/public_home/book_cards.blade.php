<div>
    <div class="fs-4 pt-3 pb-3 row">
        <div class="col"><span class="fs-2 fw-bold pe-2">{{ __('book.program') }}</span></div>
        <div class="col text-end mt-1"><a href="{{ route('public.books.index') }}">{{ __('app.show') }} <i class="ti">&#xea61;</i></a></div>
    </div>
    @if ($publicBooks->isEmpty() == false)
        <div class="card">
            <div id="carousel-indicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach ($publicBooks as $publicBook)
                        <button type="button"
                                data-bs-target="#carousel-indicators"
                                data-bs-slide-to="{{ $loop->index }}"  {{-- Use $loop->index --}}
                                @if ($loop->first) class="active" aria-current="true" @endif>
                        </button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($publicBooks as $publicBook)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <a href="{{ route('public.books.show', $publicBook) }}">
                                @if (Setting::for($publicBook)->get('poster_image_path'))
                                    <img src="{{ Storage::url(Setting::for($publicBook)->get('poster_image_path')) }}" class="w-100 h-100 object-cover" alt="{{ $publicBook->name }}" style="border-radius: 15px 15px;">
                                @else
                                    <div class="p-3 fs-1 d-flex align-items-center justify-content-center" style="min-height: 320px">{{ $publicBook->name }}</div>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <img src="{{ asset('images/empty_books.png') }}" style="border-radius: 15px; border: 1px solid #eee; padding-left: 0px; padding-right: 0px">
    @endif
</div>
