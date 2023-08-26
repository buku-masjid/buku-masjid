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

@endsection
