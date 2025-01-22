@extends('layouts.guest')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')
<section class="bg-white">
    <div class="container-md px-0">
        <div class="row p-3 p-sm-0 py-sm-3 align-items-center">
            <div class="col-auto">
                @if (Setting::get('masjid_logo_path'))
                    <div class="mb-3"><img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 80px"></div>
                @endif

            </div>
            <div class="col fs-2 fw-bold lh-sm text-dark">
                {{ Setting::get('masjid_name', config('masjid.name')) }}
            </div>
        </div>
    </div>
</section>
<div class="section-bottom pb-5">
    <div class="container-md">
        <div class="col">
            <div class="row px-3 pt-3 p-lg-0 pt-lg-3">
                <div class="col-sm-auto fs-2 fw-bold pb-3 pb-sm-0 d-sm-flex align-items-center">{{ __('report.report') }}</div>
                @include('public_reports.finance._book_navigation')
                <div class="col px-3 text-sm-end">
                    @include('public_reports.finance._time_range_navigation')
                </div>
            </div>
            <div class="summary px-3 px-lg-0 py-2 pt-lg-0">
                @include('public_reports.finance._header_summary')
            </div>
            <div class="px-3 p-lg-0">
                @include('public_reports.finance._content_summary')
            </div>
            <div class="px-4 px-sm-0">
                @include('public_reports.finance._footer_summary')
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.list-group-transparent .list-group-item {
    padding: 0.5rem 0.5rem;
}
</style>
@endsection
