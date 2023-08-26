@extends('layouts.guest')

@section('title', __('lecturing_schedule.list'))

@section('content')
@include('public_schedules._nav')

@foreach ($lecturingSchedules as $audienceCode => $groupedLecturingSchedules)
    <div class="page-header my-4">
        <h2 class="page-title">{{ __('lecturing_schedule.audience_'.$audienceCode) }}</h2>
    </div>

    @foreach($groupedLecturingSchedules as $lecturingSchedule)
        @include('public_schedules._single_'.$audienceCode)
    @endforeach
@endforeach

@endsection
