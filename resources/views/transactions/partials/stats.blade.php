<div class="row">
    <div class="col-lg-6">
        <div class="card table-responsive">
            <div class="card-header d-block text-center py-3" style="min-height: 1rem">
                <h5 class="mb-0">{{ __('report.finance_summary') }}</h5>
            </div>
            <table class="table table-sm table-bordered mb-0">
                <tr>
                    <td class="col-xs-2 text-center">{{ __('transaction.income') }}</td>
                    <td class="col-xs-2 text-center">{{ __('transaction.spending') }}</td>
                    <td class="col-xs-2 text-center">{{ __('transaction.difference') }}</td>
                </tr>
                <tr>
                    <td class="text-center strong" style="border-top: none;">{{ format_number($incomeTotal) }}</td>
                    <td class="text-center strong" style="border-top: none;">{{ format_number($spendingTotal) }}</td>
                    <td class="text-center strong" style="border-top: none;">{{ format_number($incomeTotal - $spendingTotal) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card table-responsive">
            <div class="card-header d-block text-center py-3" style="min-height: 1rem">
                <h5 class="mb-0">{{ __('transaction.origin_destination') }}</h5>
            </div>
            <table class="table table-sm table-bordered card-table mb-0">
                @foreach ($bankAccounts as $bankAccountId => $bankAccountName)
                    <tr>
                        <td class="col-6">{{ $bankAccountName }}</td>
                        <td class="text-right">
                            {{ format_number($transactions->filter(function ($transaction) use ($bankAccountId) {
                                if ($bankAccountId == 'null') {
                                    return is_null($transaction->bank_account_id);
                                }
                                return $transaction->bank_account_id == $bankAccountId;
                            })->sum(function ($transaction) {
                                return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                            })) }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
