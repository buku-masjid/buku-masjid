@php
    $currentMonthIncome = $groupedTransactions->has(1) ? $groupedTransactions[1]->sum('amount') : 0;
    $budgetDiff = auth()->activeBook()->budget - ($lastMonthBalance + $currentMonthIncome);
    $reportPeriodeCode = auth()->activeBook()->report_periode_code;
@endphp
<div class="card table-responsive">
    <table class="table table-sm table-bordered mb-0">
        <tr>
            <td class="col-xs-2 text-center">{{ __('report.current_'.$reportPeriodeCode.'_budget') }}</td>
            <td class="col-xs-2 text-center">{{ __('report.current_periode_income_total') }}</td>
            <td class="col-xs-2 text-center strong {{ $budgetDiff > 0 ? 'text-red' : 'text-success' }}">
                @if ($budgetDiff > 0)
                    {{ __('report.current_periode_budget_remaining') }}
                @else
                    {{ __('report.current_periode_budget_excess') }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="text-center lead" style="border-top: none;">{{ format_number(auth()->activeBook()->budget) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ format_number($lastMonthBalance + $currentMonthIncome) }}</td>
            <td class="text-center lead strong {{ $budgetDiff > 0 ? 'text-red' : 'text-success' }}" style="border-top: none;">
                {{ format_number(abs($budgetDiff)) }}
            </td>
        </tr>
    </table>
</div>
