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
                    <p class="m-0 px-2">{{ 'Saldo per ' .now()->startOfWeek()->isoFormat('dddd, D MMMM Y') }}</p>
                </td>
                <td class="col-1 text-right">
                    <p class="m-0 px-2">{{ number_format($thisWeekBalance) }}</p>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <p class="m-0 px-2">
                        Pemasukan hingga hari ini
                    </p>
                </td>
                <td class="col-1 text-right">
                    <p class="m-0 px-2">
                        {{ number_format($thisWeekIncome) }}
                    </p>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <p class="m-0 px-2">
                        Pengeluaran hingga hari ini
                    </p>
                </td>
                <td class="col-1 text-right">
                    <p class="m-0 px-2">
                        -{{ number_format($thisWeekSpending) }}
                    </p>
                </td>
            </tr>
            <tr>
                <td class="col-4">
                    <p class="m-0 px-2">
                        Saldo per hari ini ({{ now()->isoFormat('dddd, D MMMM Y') }})
                    </p>
                </td>
                <td class="col-1 text-right">
                    <p class="m-0 px-2">
                        {{ number_format($allBalance) }}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
