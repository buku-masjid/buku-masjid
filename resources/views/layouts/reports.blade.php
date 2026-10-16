@extends('layouts.app')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')
<div class="row g-0">
    <div class="col-12 col-md-3">
        @include('layouts._report_nav')
        <hr class="my-2">
    </div>
    <div class="col-12 col-md-9">
        @yield('content-report')
    </div>
</div>
<div class="my-6"></div>
@endsection

@push('styles')
<style>
.nav-vertical.nav-pills {margin: 0;}
</style>
@endpush
