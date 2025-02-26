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
                    <span id="current_periode_budget_label">{{ $currentPeriodeBudgetLabel }}</span>
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
</table>
