@extends('layouts.app')

@section('title', __('bank_account.bank_account'))

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $bankAccount->name }}</h1>
    <div class="page-subtitle">{{ __('bank_account.bank_account') }}</div>
    <div class="page-options d-flex">
        @can('update', $bankAccount)
            {{ link_to_route('bank_accounts.show', __('bank_account_balance.create'), [$bankAccount, 'action' => 'create_bank_account_balance'], ['id' => 'create-bank_account_balance', 'class' => 'btn btn-success mr-2']) }}
        @endcan
        {{ link_to_route('bank_accounts.index', __('bank_account.back_to_index'), [], ['class' => 'btn btn-secondary']) }}
    </div>
</div>

<div class="card table-responsive">
    <table class="table table-sm table-bordered mb-0">
        <tr>
            <td class="col-xs-2 text-center">{{ __('bank_account.name') }}</td>
            <td class="col-xs-2 text-center">{{ __('bank_account.number') }}</td>
            <td class="col-xs-2 text-center">{{ __('bank_account.account_name') }}</td>
            <td class="col-xs-2 text-center">{{ __('app.status') }}</td>
        </tr>
        <tr>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->name }}</td>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->number }}</td>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->account_name }}</td>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->status }}</td>
        </tr>
    </table>
</div>

@if ($bankAccount->description)
    <div class="alert alert-info"><strong>{{ __('app.description') }}:</strong><br>{{ $bankAccount->description }}</div>
@endif

<div class="page-header">
    <h2 class="page-title">{{ __('bank_account_balance.bank_account_balance') }}</h2>
</div>

<div class="card table-responsive">
    <table class="table table-sm table-hover mb-0">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th class="text-nowrap">{{ __('bank_account_balance.date') }}</th>
                <th class="text-nowrap text-right">{{ __('transaction.amount') }}</th>
                <th class="">{{ __('app.description') }}</th>
                <th class="">{{ __('app.created_by') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bankAccountBalances as $key => $bankAccountBalance)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-nowrap">{{ $bankAccountBalance->date }}</td>
                <td class="text-nowrap text-right">{{ $bankAccountBalance->amount_string }}</td>
                <td class="">{{ $bankAccountBalance->description }}</td>
                <td class="">{{ $bankAccountBalance->creator->name }}</td>
                <td class="text-center text-nowrap">
                    @can('update', $bankAccount)
                        {{ link_to_route(
                            'bank_accounts.show',
                            __('app.edit'),
                            [$bankAccount, 'action' => 'edit_bank_account_balance', 'bank_account_balance_id' => $bankAccountBalance->id],
                            [
                                'id' => 'edit-bank_account_balance-'.$bankAccountBalance->id,
                                'class' => 'btn btn-sm btn-warning',
                            ]
                        ) }}
                    @endcan
                </td>
            </tr>
            @empty
            <tr><td colspan="6">{{ __('bank_account_balance.empty') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@includeWhen(request('action'), 'bank_accounts._bank_account_balance_forms')
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
{{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#bankAccountBalanceModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('.date-select').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
})();
</script>
@endpush
