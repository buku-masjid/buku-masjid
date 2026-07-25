@extends('layouts.app')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3">
        @include('layouts._report_nav')
        <hr class="my-2">
    </div>
    <div class="col-lg-9">
        @yield('content-report')
    </div>
</div>
@endsection

@push('styles')
<style>
.list-group-transparent .list-group-item {
    padding: 0.5rem 0.5rem;
}
</style>
@endpush
