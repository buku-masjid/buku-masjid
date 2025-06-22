<div>
    <div class="fs-4 pt-3 pb-3 row">
        <div class="col"><span class="fs-2 fw-bold pe-2">{{ __('book.program') }}</span></div>
        <div class="col text-end mt-1"><a href="{{ route('public.books.index') }}">{{ __('app.show') }} <i class="ti">&#xea61;</i></a></div>
    </div>
    @if ($publicBooks->isEmpty() == false)
        
            <div id="carousel-indicators" class="carousel slide" data-bs-ride="carousel">
                <div class="card">
                    <div class="carousel-inner">
                        @foreach ($publicBooks as $publicBook)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <a href="{{ route('public.books.show', $publicBook) }}" class="position-relative d-block p-3">
                                    @if (Setting::for($publicBook)->get('poster_image_path'))
                                        <img src="{{ Storage::url(Setting::for($publicBook)->get('poster_image_path')) }}" class="w-100 h-100 object-cover" alt="{{ $publicBook->name }}" style="border-radius: 15px;">
                                    @else
                                        <div class="p-3 fs-1 d-flex align-items-center justify-content-center bg-info-lt" style="min-height: 320px; border-radius: 15px;">{{ $publicBook->name }}</div>
                                    @endif
                                    @if ($publicBook->budget > 0)
                                    <div class="w-100 pt-3 px-2" >
                                        <!-- <h5>{{ $publicBook->name }}</h5>-->
                                        <div class="progress progress-bar-striped rounded-pill" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped rounded-pill bg-{{ $publicBook->progressPercentColor }}" style="width: {{ $publicBook->progressPercent }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3 ">
                                            <span>Terkumpul: <strong>{{ config('money.currency_code') }} {{ format_number($publicBook->income_total) }}</strong></span>
                                            <span>Target: <strong>{{ config('money.currency_code') }} {{ format_number($publicBook->budget) }}</strong></span>
                                        </div>
                                    </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if ($publicBooks->count() > 1)
                    <div class="carousel-indicators position-relative mt-2 mb-2">
                        @foreach ($publicBooks as $publicBook)
                            <button type="button"
                                    data-bs-target="#carousel-indicators"
                                    data-bs-slide-to="{{ $loop->index }}"
                                    @if ($loop->first) class="active" aria-current="true" @endif
                                    aria-label="Slide {{ $loop->iteration }}"
                                    style="background-color: #888;">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
    @else
        <img src="{{ asset('images/empty_books.png') }}" style="border-radius: 15px; border: 1px solid #eee; padding-left: 0px; padding-right: 0px">
    @endif
</div>
