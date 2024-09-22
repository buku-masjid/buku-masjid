@extends('layouts.print')

@section('title')
    {{ __('dashboard.dashboard') }}
    {{ get_date_range_text($startDate->format('Y-m-d'), $endDate->format('Y-m-d')) }}
@endsection

@section('content')
<htmlpageheader name="wpHeader">
    @include('reports.partials.letterhead')

    <h2 class="text-center strong">
        @yield('title')
    </h2>
</htmlpageheader>

@include('reports.finance._internal_content_dashboard', ['isForPrint' => true])

@include('reports.finance._pdf_signature_content')

<htmlpagefooter name="wpFooter">
    @if (Setting::for(auth()->activeBook())->get('has_pdf_page_number') != '0')
        <div class="text-right">{{ __('report.page') }} {PAGENO}/{nb}</div>
    @endif
</htmlpagefooter>
@endsection

@section('style')
<style>
    @page {
        size: auto;
        margin-top: @if($showLetterhead) 170px; @else 100px; @endif
        margin-bottom: 70px;
        margin-left: 50px;
        margin-right: 50px;
        margin-header: 40px;
        margin-footer: 40px;
        header: html_wpHeader;
        footer: html_wpFooter;
    }
</style>
@endsection
