@extends('layouts.print')

@php
    $reportTitle = auth()->activeBook()->report_titles['finance_summary'] ?? __('report.in_weeks');
    $reportTitleWithPeriod = $reportTitle.' '.__('time.period').' '.get_date_range_text($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
@endphp

@section('title', $reportTitleWithPeriod)

@section('content')
<htmlpageheader name="wpHeader">
    @include('reports.partials.letterhead')

    <h2 class="text-center strong">
        {{ $reportTitleWithPeriod }}
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
        <div class="text-end">{{ __('report.page') }} {PAGENO}/{nb}</div>
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
