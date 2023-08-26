<div class="list-group list-group-transparent list-group-horizontal-md mb-0 text-uppercase">
    <a href="{{ route('public_schedules.today', Request::all()) }}" class="list-group-item list-group-item-action {{ in_array(Request::segment(2), [null, 'hari_ini']) ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-home"></i></span>{{ __('time.today') }} - {{ Carbon\Carbon::now()->isoFormat('dddd, D MMM Y') }}
    </a>
    <a href="{{ route('public_schedules.tomorrow', Request::all()) }}" class="list-group-item list-group-item-action {{ in_array(Request::segment(2), ['besok']) ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-home"></i></span>{{ __('time.tomorrow') }} - {{ Carbon\Carbon::tomorrow()->isoFormat('dddd, D MMM Y') }}
    </a>
</div>
