<div class="card table-responsive">
    <table class="table table-sm table-bordered mb-0">
        <tr>
            <td class="col-xs-2 text-center">{{ __('transaction.income') }}</td>
            <td class="col-xs-2 text-center">{{ __('transaction.spending') }}</td>
            <td class="col-xs-2 text-center">{{ __('transaction.difference') }}</td>
        </tr>
        <tr>
            <td class="text-center lead" style="border-top: none;">{{ format_number($incomeTotal) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ format_number($spendingTotal) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ format_number($incomeTotal - $spendingTotal) }}</td>
        </tr>
    </table>
</div>
