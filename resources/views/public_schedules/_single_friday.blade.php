<div class="card fw-bold p-3 mb-2 shadow-lg position-relative">
    <div class="row">
        <div class="col-auto lh-1">
            <span class="badge rounded-pill bg-orange mb-3 me-3">{{ __('lecturing.audience_friday') }}</span>
            <h3 class="p-0 m-0">{{ $lecturing->day_name }}, {{ $lecturing->full_date }}</h3>
            <span class="date">{{ $lecturing->start_time }} (waktu setempat)</span>
        </div>
        <div class="col-lg px-lg-5 px-3 py-lg-3 py-4">
            <div class="fs-3">
                <h3>{{ __('lecturing.title') }}</h3>
                <p class="display-6 fw-thin">"{{ $lecturing->title }}" </p>
            </div>
            <div class="row pt-4 mt-4 border-top">
                <div class="col">
                    <h1 class="fs-3 bm-txt-primary fw-bold p-0 mb-1 lh-1">{{ $lecturing->lecturer_name }}</h1>
                    <div class="fs-4 text-secondary">
                        @if ($lecturing->lecturer_name == $lecturing->imam_name)
                            {{ __('lecturing.friday_lecturer_and_imam') }}
                        @else
                            {{ __('lecturing.friday_lecturer_name') }}
                        @endif
                    </div>
                </div>
                @if ($lecturing->lecturer_name != $lecturing->imam_name)
                    <div class="col">
                        <h1 class="fs-3 bm-txt-primary fw-bold p-0 mb-1 lh-1">{{ $lecturing->imam_name }}</h1>
                        <div class="fs-4 text-secondary">{{ __('lecturing.imam_name') }}</div>
                    </div>
                @endif
                <div class="col">
                    <h1 class="fs-3 bm-txt-primary fw-bold p-0 mb-1 lh-1">{{ $lecturing->muadzin_name }}</h1>
                    <span class="fs-4 text-secondary">{{ __('lecturing.muadzin_name') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
