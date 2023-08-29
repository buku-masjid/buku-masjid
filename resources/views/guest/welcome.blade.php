@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<div class="jumbotron p-4 p-md-5 text-dark rounded bg-lightgray">
    <div class="col-md-6 px-0">
        <h2 class="font-italic">
            @yield('title'),<br> {{ config('masjid.name') }}
        </h2>
        <p class="lead mb-0">
            <a class="btn btn-lg btn-success mr-2" href="{{ route('public_reports.index') }}" role="button">{{ __('report.view_report') }}</a>
            <a class="btn btn-lg btn-info" href="{{ route('public_schedules.index') }}" role="button">{{ __('lecturing_schedule.lecturing_schedule') }}</a>
        </p>
    </div>
</div>
@endsection
