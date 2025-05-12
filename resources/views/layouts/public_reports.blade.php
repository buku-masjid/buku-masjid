@extends('layouts.guest')

@section('title')
@yield('subtitle', __('report.reports'))
@endsection

@section('content')<section class="bg-white">
    <div class="container-md">
        <div class="row p-3 p-sm-0 py-sm-3 align-items-center">
            <div class="col-auto">
                @if (Setting::get('masjid_logo_path'))
                    <div class="mb-3">
                        <a href="{{ route('public_reports.index') }}">
                            <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 80px">
                        </a>
                    </div>
                @endif

            </div>
            <div class="col">
                <p class="fs-2 fw-bold lh-sm text-dark mb-1">{{ Setting::get('masjid_name', config('masjid.name')) }}</p>
                {{ Setting::get('masjid_address') }}
            </div>
        </div>
    </div>
</section>

<div class="section-bottom pb-5">
    <div class="container-md">
        <div class="col">
            <div class="row px-3 pt-3 p-lg-0 pt-lg-3">
                <div class="col-sm-auto fs-2 fw-bold pb-3 pb-sm-0 d-sm-flex align-items-center">{{ __('report.report') }}</div>
                <div class="col-sm d-grid d-sm-flex align-items-center pb-2 pb-sm-0">
                    @include('public_reports.finance._book_navigation')
                </div>
                <div class="d-none col-sm text-center d-grid align-items-center text-sm-end pb-2 pb-sm-0">
                    {{ $startDate->isoFormat('dddd, D MMM Y') }} - {{ $endDate->isoFormat('dddd, D MMM Y') }}
                </div>
                <div class="col-sm px-3 d-grid align-items-center text-sm-end">
                    @include('public_reports.finance._time_range_navigation')
                </div>
            </div>
            <div class="summary px-3 px-lg-0 py-2 pt-lg-0">
                @include('public_reports.finance._header_summary')
            </div>
            <div class="px-3 p-lg-0">
                @include('public_reports.finance._report_navigation')
            </div>
            <div class="px-3 p-lg-0">
                @yield('content-report')
            </div>
        </div>
    </div>
</div>
@endsection
