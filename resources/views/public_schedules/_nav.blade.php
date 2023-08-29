<div class="list-group list-group-transparent list-group-horizontal-md mb-0 text-uppercase">
    <a href="{{ route('public_schedules.today', Request::all()) }}" class="list-group-item list-group-item-action {{ in_array(Request::segment(2), [null, 'hari_ini']) ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-calendar"></i></span>{{ __('time.today') }}
    </a>
    <a href="{{ route('public_schedules.tomorrow', Request::all()) }}" class="list-group-item list-group-item-action {{ in_array(Request::segment(2), ['besok']) ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-calendar"></i></span>{{ __('time.tomorrow') }}
    </a>
    <a href="{{ route('public_schedules.this_week', Request::all()) }}" class="list-group-item list-group-item-action {{ Request::segment(2) == 'pekan_ini' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-calendar"></i></span>{{ __('time.this_week') }}
    </a>
    <a href="{{ route('public_schedules.next_week', Request::all()) }}" class="list-group-item list-group-item-action {{ Request::segment(2) == 'pekan_depan' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-calendar"></i></span>{{ __('time.next_week') }}
    </a>
</div>
