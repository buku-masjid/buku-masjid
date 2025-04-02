@extends('layouts.print')

@section('title', __('report.finance_detailed'))

@section('content')
<htmlpageheader name="wpHeader">
    @include('reports.partials.letterhead')

    <h2 class="text-center strong">
        @if (isset(auth()->activeBook()->report_titles['finance_detailed']))
            {{ auth()->activeBook()->report_titles['finance_detailed'] }}
        @else
            {{ __('report.finance_detailed') }}
        @endif
    </h2>
</htmlpageheader>

@php
    $lastWeekDate = null;
@endphp
@foreach($groupedTransactions as $weekNumber => $weekTransactions)
    @php
        $lastWeekDate = $lastWeekDate ?: $lastMonthDate;
    @endphp
    <div class="card-header">
        <h3 class="card-title">{{ __('time.week') }} {{ $weekNumber + 1 }} ({{ $weekLabels[$weekNumber] }})</h3>
    </div>
    @include('reports.finance._internal_content_detailed')
    @php
        $lastWeekDate = Carbon\Carbon::parse($weekTransactions->last()->last()->date);
    @endphp
    @if ($weekNumber != $groupedTransactions->keys()->last())
        <pagebreak />
    @endif
@endforeach

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
