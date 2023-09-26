<div class="page-header">
    <h3 class="page-title">{{ __('report.summary_weekly') }}</h3>
    <div class="page-options d-flex">
        <a class="btn btn-sm btn-success" href="{{ route('public_reports.in_weeks') }}"
            role="button">{{ __('app.show') }}</a>
    </div>
</div>
<div class="card">
    <table class="table table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4">
                    <span id="start_week_label">{{ 'Saldo per ' .now()->startOfWeek()->isoFormat('dddd, D MMMM Y') }}</span>
                </td>
                <td class="col-1 text-right">
                    <span id="start_week_balance">{{ number_format($thisWeekBalance) }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span>Pemasukan hingga hari ini</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_week_income_total">{{ number_format($thisWeekIncome) }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span>Pengeluaran hingga hari ini</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_week_spending_total">{{ $thisWeekSpending ? '-'.number_format($thisWeekSpending) : 0 }}</span>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <span id="current_balance_label">Saldo per hari ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</span>
                </td>
                <td class="col-1 text-right">
                    <span id="current_balance">{{ number_format($allBalance) }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
