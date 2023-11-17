<div class="page-header">
    <h3 class="page-title">{{ __('report.summary_weekly') }}</h3>
    <div class="page-options d-flex">
        <a class="btn btn-sm btn-success" href="{{ route('public_reports.finance.detailed') }}"
            role="button">{{ __('app.show') }}</a>
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
