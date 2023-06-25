@extends('layouts.app')

@section('title', __('bank_account.list'))

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ __('bank_account.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $bankAccounts->total() }} {{ __('bank_account.bank_account') }}</div>
    <div class="page-options d-flex">
        @if (Request::has('action') == false)
            @can('create', new App\Models\BankAccount)
            {{ link_to_route('bank_accounts.index', __('bank_account.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
            @endcan
        @endif
    </div>
</div>

<div class="card table-responsive">
    <table class="table table-sm table-hover mb-0">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th class="text-nowrap">{{ __('bank_account.name') }} / {{ __('bank_account.number') }}</th>
                <th class="text-nowrap">{{ __('bank_account.account_name') }}</th>
                <th class="text-right">{{ __('bank_account_balance.amount') }}</th>
                <th class="text-right">{{ __('bank_account_balance.date') }}</th>
                <th class="text-center">{{ __('app.status') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bankAccounts as $key => $bankAccount)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-nowrap">{{ $bankAccount->name }} / {{ $bankAccount->number }}</td>
                <td class="text-nowrap">{{ $bankAccount->account_name }}</td>
                <td class="text-right">{{ optional($bankAccount->lastBalance)->amount_string }}</td>
                <td class="text-right">{{ optional($bankAccount->lastBalance)->date }}</td>
                <td class="text-nowrap text-center">{{ $bankAccount->status }}</td>
                <td class="text-center text-nowrap">
                    @can('view', $bankAccount)
                        {{ link_to_route(
                            'bank_accounts.show',
                            __('app.show'),
                            $bankAccount,
                            [
                                'id' => 'show-bank_account-'.$bankAccount->id,
                                'class' => 'btn btn-sm btn-secondary',
                            ]
                        ) }}
                    @endcan
                    @can('update', $bankAccount)
                        {{ link_to_route(
                            'bank_accounts.index',
                            __('app.edit'),
                            ['action' => 'edit', 'id' => $bankAccount->id],
                            [
                                'id' => 'edit-bank_account-'.$bankAccount->id,
                                'class' => 'btn btn-sm btn-warning',
                            ]
                        ) }}
                    @endcan
                </td>
            </tr>
            @empty
            <tr><td colspan="5">{{ __('bank_account.not_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $bankAccounts->links() }}
@includeWhen(Request::has('action'), 'bank_accounts.forms')
@endsection

@push('scripts')
<script>
(function () {
    $('#bankAccountModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
