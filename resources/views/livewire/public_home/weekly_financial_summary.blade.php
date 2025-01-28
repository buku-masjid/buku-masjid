<?php
/*
<div class="page-header">
    <h3 class="page-title">{{ __('report.summary_weekly') }}</h3>
    <div class="page-options d-flex">
        @if ($bookVisibility == 'public')
            <a class="btn btn-sm btn-success" href="{{ route('public_reports.finance.detailed') }}" role="button">{{ __('app.show') }}</a>
        @endif
    </div>
</div>
<div class="card mb-0">
    <table class="table table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4">
                    <span id="start_week_label">{{ __('report.balance_per_date', ['date' => $startWeek->isoFormat('dddd, D MMMM Y')]) }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="start_week_balance">{{ format_number($startWeekBalance) }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span>{{ __('report.current_week_income_total') }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_week_income_total">{{ format_number($currentWeekIncomeTotal) }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span>{{ __('report.current_week_spending_total') }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_week_spending_total">{{ format_number($currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0) }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span id="current_balance_label">{{ __('report.today_balance', ['date' => $today->isoFormat('dddd, D MMMM Y')]) }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_balance">{{ format_number($currentBalance) }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
*/
?>

<div class="col-lg-9">
    <div class="fs-4 pt-3 pb-3 d-flex align-items-center">
        <span class="fs-2 fw-bold pe-2">Laporan Pekan Ini</span>
        <span class="badge bg-cyan-lt">Kegiatan Rutin</span>
    </div>
    <div class="row align-items-end">
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ __('transaction.income') }}<br>
                <span class="date">{{ __('report.current_week_income_total') }}</span>
                <h1 class="pt-4 bm-txt-primary fw-bolder">
                    <span id="current_week_income_total">{{ config('money.currency_code') }} {{ format_number($currentWeekIncomeTotal) }}</span>
                </h1>
            </div>
        </div>
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                Pengeluaran<br>
                <span class="date">{{ __('report.current_week_spending_total') }}</span>
                <h1 class="pt-4 bm-txt-out fw-bolder">
                    <span id="current_week_spending_total">{{ config('money.currency_code') }} {{ format_number($currentWeekSpendingTotal ? -$currentWeekSpendingTotal : 0) }}</span>
                </h1>
            </div>
        </div>
        <div class="col-lg ps-sm-0">
            <div class="card fs-3 fw-bold p-3 mb-2 position-relative shadow-lg">
                @if ($bookVisibility == 'public')
                    <a class="fs-6 btn btn-sm bm-btn btn-outline-cyan position-absolute end-0 me-3 px-2 py-1" href="{{ route('public_reports.finance.detailed') }}" role="button">{{ __('app.show') }}</a>
                @endif
                Saldo Terakhir<br>
                <span class="date" id="current_balance_label">{{ __('report.today_balance', ['date' => $today->isoFormat('dddd, D MMMM Y')]) }}</span>
                <h1 class="pt-4 bm-txt-netral fw-bolder">
                    <span id="current_balance">{{ config('money.currency_code') }} {{ format_number($currentBalance) }}</span>
                </h1>
            </div>
        </div>
    </div>
    <a class="d-sm-none">
        <button type="button" class="btn bm-btn btn-sm btn-ghost-cyan mt-2">
            &nbsp;Lihat Semua Laporan <i class="ti">&#xea1c;</i>&nbsp;
        </button>
    </a>
</div>
