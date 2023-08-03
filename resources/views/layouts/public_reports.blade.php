@extends('layouts.guest')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')
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
