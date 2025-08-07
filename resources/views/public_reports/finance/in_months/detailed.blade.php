@extends('layouts.public_reports')

@section('subtitle', __('report.weekly'))

@section('content-report')
    <div class="accordion accordion-flush">
        <div class="d-none d-sm-block sticky-top border-bottom border-1" style="background-color: #f8f8f8;">
            <div class="row pe-4 py-3 g-0" style="padding-left: 1.25rem">
                <div class="col-auto d-none"></div>
                <div class="col bm-fade fs-3 fw-bold rounded">{{ __('transaction.transaction') }}</div>
                <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.income') }}</div>
                <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block">{{ __('transaction.spending') }}</div>
                <div class="col-2 p-0 fs-3 fw-bold rounded text-end d-none d-lg-block pe-2">Saldo</div>
            </div>
        </div>
        @php
            $lastWeekDate = null;
        @endphp
        @foreach($groupedTransactions as $weekNumber => $weekTransactions)
            @php
                $lastWeekDate = $lastWeekDate ?: $lastMonthDate;
            @endphp
            <div class="accordion-item card mb-2">
                <div class="accordion-header">
                    <button class="accordion-button collapsed fs-2 bm-txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#week_number_{{ 1 + $weekNumber }}" aria-expanded="false">
                        <span class="fw-bold">{{ __('time.week') }} {{ $weekNumber + 1 }}</span> &nbsp;&nbsp;<span class="text-dark fs-5">{{ $weekLabels[$weekNumber] }}</span>
                    </button>
                </div>
                <div id="week_number_{{ 1 + $weekNumber }}" class="px-3 px-lg-4 accordion-collapse collapse {{ $weekNumber == $groupedTransactions->keys()->first() ? 'show' : '' }}">
                    @include('public_reports.finance._public_content_detailed')
                    @php
                        $lastWeekDate = Carbon\Carbon::parse($weekTransactions->last()->last()->date);
                    @endphp
                </div>
            </div>
        @endforeach
        @include('public_reports.finance._footer_summary')
    </div>
@endsection
