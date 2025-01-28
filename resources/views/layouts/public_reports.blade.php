@extends('layouts.guest')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')

<div class="page-header mt-0">
    <h1 class="page-title">{{ __('book.book') }}: {{ auth()->activeBook()->name }}</h1>
    <div class="page-options d-flex">
        <span class="badge text-uppercase badge-primary mr-1" title="{{ __('report.periode') }}: {{ __('report.'.auth()->activeBook()->report_periode_code) }}">
            {{ __('report.'.auth()->activeBook()->report_periode_code) }}
        </span>
        <span class="badge text-uppercase badge-warning text-dark" title="{{ __('report.start_week_day') }}: {{ __('time.days.'.auth()->activeBook()->start_week_day_code) }}">
            {{ __('time.days.'.auth()->activeBook()->start_week_day_code) }}
        </span>
    </div>
</div>
@include('layouts._public_report_nav')
<hr class="mt-2 mb-4">
@yield('content-report')
@endsection

@section('styles')
<style>
.list-group-transparent .list-group-item {
    padding: 0.5rem 0.5rem;
}
</style>
@endsection
