@extends('layouts.print')

@section('title', __('report.in_weeks'))

@section('content')
<htmlpageheader name="wpHeader">
    @include('reports.partials.letterhead')

    <h2 class="text-center strong">
        @if (isset(auth()->activeBook()->report_titles['finance_summary']))
            {{ auth()->activeBook()->report_titles['finance_summary'] }}
        @else
            {{ __('report.in_weeks') }}
        @endif
    </h2>
</htmlpageheader>

@if ($showBudgetSummary)
    <br>
    @include('reports.finance._internal_periode_summary')
    <br>
@endif

<div class="">
    @include('reports.finance._internal_content_summary')
</div>

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
        margin-bottom: 20px;
        margin-left: 50px;
        margin-right: 50px;
        margin-header: 40px;
        margin-footer: 40px;
        header: html_wpHeader;
        footer: html_wpFooter;
    }
</style>
@endsection
