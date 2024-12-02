<div class="card shadow-lg" style="border-radius:1em;height: 10em">
    <div class="card-body p-3 row">
        <div class="col-4 d-flex align-items-center">
            <img src="{{ asset('images/'.$partner->gender_code.'-icon.svg') }}">
        </div>
        <div class="col-8">
            <span class="badge p-2 bg-blue-lighter text-dark float-right">{{ $partner->status }}</span>
            <div>
                <div class="text-muted small">{{ __('partner.phone') }}</div>
                <strong>{{ $partner->phone ? link_to('https://wa.me/'.str_replace([' ', '+', '(', ')'], '', $partner->phone), $partner->phone) : '' }}</strong>
            </div>
            <div>
                <div class="text-muted small">{{ __('partner.work') }}</div>
                <strong>{{ $partner->work ?: '-' }}</strong>
            </div>
            <div>
                <div class="text-muted small">{{ __('app.gender') }}</div>
                <strong>{{ $partner->gender }}</strong>
            </div>
        </div>
    </div>
</div>
