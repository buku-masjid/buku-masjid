@extends('layouts.guest')

@section('title', __('lecturing_schedule.list'))

@section('content')
@include('public_schedules._nav')

@foreach ($audienceCodes as $audienceCode => $audience)
    @if (isset($lecturingSchedules[$audienceCode]))
        <div class="page-header my-4">
            <h2 class="page-title">{{ __('lecturing_schedule.audience_'.$audienceCode) }}</h2>
        </div>
        @foreach($lecturingSchedules[$audienceCode] as $lecturingSchedule)
            @include('public_schedules._single_'.$audienceCode)
        @endforeach
    @endif
@endforeach

@if ($lecturingSchedules->isEmpty())
    <p class="my-4">
        {{ __('lecturing_schedule.empty') }}
        {{ in_array(Request::segment(2), [null, 'hari_ini']) ? __('time.today').'.' : '' }}
        {{ in_array(Request::segment(2), ['besok']) ? __('time.tomorrow').'.' : '' }}
        {{ Request::segment(2) == 'pekan_ini' ? __('time.this_week').'.' : '' }}
        {{ Request::segment(2) == 'pekan_depan' ? __('time.next_week').'.' : '' }}
    </p>
@endif

@endsection
