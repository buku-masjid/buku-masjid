@if ($currentBudget)
    <div class="mb-4">
        <h3>{{ __('book.progress') }}</h3>
        <div class="progress progress-bar-striped rounded-pill" title="{{ __('report.current_periode_income_total') }}: {{ $progressPercent }}%" style="height: 20px;">
            <div class="progress-bar progress-bar-striped rounded-pill bg-{{ $progressPercentColor }}" style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>
@endif
<div class="row align-items-end">
    @if ($currentBudget)
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ $currentPeriodeBudgetLabel }}
                <h1 class="pt-4 bm-txt-netral fw-bolder">
                    <span id="current_budget">{{ config('money.currency_code') }}{{ format_number($currentBudget) }}</span>
                </h1>
                <span class="date">{{ config('money.currency_code') }}{{ format_number($currentBudget) }}</span>
            </div>
        </div>
    @endif
    <div class="col-lg ps-sm-0">
        <div class="card fw-bold p-3 mb-2 shadow-lg">
            {{ __('report.current_periode_income_total') }}
            <h1 class="pt-4 bm-txt-primary fw-bolder">
                <span id="current_income_total">{{ config('money.currency_code')}}{{ format_number($currentIncomeTotal) }}</span>
            </h1>
            <span class="date">{{ config('money.currency_code') }}{{ format_number($currentIncomeTotal) }}</span>
        </div>
    </div>
    @if ($currentBudget)
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ $currentBudgetRemainingLabel }}
                <h1 class="pt-4 bm-txt-out fw-bolder">
                    <span id="current_diff">{{ config('money.currency_code') }}{{ format_number($budgetDifference) }}</span>
                </h1>
                <span class="date">{{ config('money.currency_code') }}{{ format_number($budgetDifference) }}</span>
            </div>
        </div>
    @else
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ __('report.current_periode_spending_total') }}
                <h1 class="pt-4 bm-txt-out fw-bolder">
                    <span id="current_income_total">{{ config('money.currency_code') }}{{ format_number($currentSpendingTotal ? -$currentSpendingTotal : 0) }}</span>
                </h1>
                <span class="date">{{ config('money.currency_code') }}{{ format_number($currentSpendingTotal ? -$currentSpendingTotal : 0) }}</span>
            </div>
        </div>
        <div class="col-lg ps-sm-0">
            <div class="card fw-bold p-3 mb-2 shadow-lg">
                {{ __('report.today_balance', ['date' => $today->isoFormat('dddd, D MMM Y')]) }}
                <h1 class="pt-4 bm-txt-primary fw-bolder">
                    <span id="current_income_total">{{ config('money.currency_code') }}{{ format_number($currentBalance) }}</span>
                </h1>
                <span class="date">{{ config('money.currency_code') }}{{ format_number($currentBalance) }}</span>
            </div>
        </div>
    @endif
<div>
<?php /*
<table class="table table-sm card-table mb-0">
    <tbody>
        @if ($currentBudget)
            <tr>
                <td colspan="2">
                    <div class="progress progress-sm" title="{{ __('report.current_periode_income_total') }}: {{ $progressPercent }}%">
                        <div class="progress-bar bg-{{ $progressPercentColor }}" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span id="current_periode_budget_label">asdasd{{ $currentPeriodeBudgetLabel }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_periode_budget">{{ format_number($currentBudget) }}</span>
                </td>
            </tr>
        @else
            @if (!$isAlltime)
                <tr>
                    <td class="col-4">
                        <span id="start_periode_label">{{ __('report.balance_per_date', ['date' => $start->subDay()->isoFormat('dddd, D MMM Y')]) }}</span>
                    </td>
                    <td class="col-1 text-right">
                        <span id="start_periode_balance">{{ format_number($startBalance) }}</span>
                    </td>
                </tr>
            @endif
        @endif
        <tr>
            <td class="col-4">
                <span>{{ __('report.current_periode_income_total') }}</span>
            </td>
            <td class="col-1 text-right">
                <span id="current_periode_income_total">{{ format_number($currentIncomeTotal) }}</span>
            </td>
        </tr>
        @if ($currentBudget)
            <tr class="{{ $budgetDifferenceColorClass }}">
                <td class="col-4 strong">
                    <span id="current_periode_budget_remaining_label">{{ $currentBudgetRemainingLabel }}</span>
                </td>
                <td class="col-1 text-right strong">
                    <span id="current_periode_budget_remaining">{{ format_number(abs($budgetDifference)) }}</span>
                </td>
            </tr>
        @else
            <tr>
                <td class="col-4">
                    <span>{{ __('report.current_periode_spending_total') }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_periode_spending_total">{{ format_number($currentSpendingTotal ? -$currentSpendingTotal : 0) }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span id="current_balance_label">{{ __('report.today_balance', ['date' => $today->isoFormat('dddd, D MMM Y')]) }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_balance">{{ format_number($currentBalance) }}</span>
                </td>
            </tr>
        @endif
    </tbody>
</table> */ ?>
<script>
    /*function shortenNominalConcise(amount) {
        const formatter = new Intl.NumberFormat('id-ID', { 
            notation: 'compact', 
            maximumFractionDigits: 2 // decimal places.
        });
        return `Rp${formatter.format(amount)}`;
    }

    const currentBudget = document.getElementById('current_budget');
    currentBudget.textContent = shortenNominalConcise(parseInt({{$currentBudget}}));

    const currentIncomeTotal = document.getElementById('current_income_total');
    currentIncomeTotal.textContent = shortenNominalConcise(parseInt({{$currentIncomeTotal}})); */

    
</script>
