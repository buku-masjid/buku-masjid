@extends('layouts.app')

@section('title', __('bank_account.list'))

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ __('bank_account.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $bankAccounts->count() }} {{ __('bank_account.bank_account') }}</div>
    <div class="page-options d-flex">
        @if (Request::has('action') == false)
            @can('create', new App\Models\BankAccount)
            {{ link_to_route('bank_accounts.index', __('bank_account.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
            @endcan
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card table-responsive">
            <table class="table table-sm table-responsive-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('bank_account.name') }} / {{ __('bank_account.number') }}</th>
                        <th class="text-nowrap">{{ __('bank_account.account_name') }}</th>
                        <th>{{ __('app.description') }}</th>
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
                        <td>{{ $bankAccount->description }}</td>
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
                    <tr><td colspan="4">{{ __('bank_account.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @includeWhen(Request::has('action'), 'bank_accounts.forms')
    </div>
</div>
@endsection
