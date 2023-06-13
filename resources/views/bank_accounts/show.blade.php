@extends('layouts.app')

@section('title', __('bank_account.bank_account'))

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $bankAccount->name }}</h1>
    <div class="page-subtitle">{{ __('bank_account.bank_account') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('bank_accounts.index', __('bank_account.back_to_index'), [], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>

<div class="card table-responsive">
    <table class="table table-sm table-bordered mb-0">
        <tr>
            <td class="col-xs-2 text-center">{{ __('bank_account.name') }}</td>
            <td class="col-xs-2 text-center">{{ __('bank_account.number') }}</td>
            <td class="col-xs-2 text-center">{{ __('bank_account.account_name') }}</td>
        </tr>
        <tr>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->name }}</td>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->number }}</td>
            <td class="text-center lead" style="border-top: none;">{{ $bankAccount->account_name }}</td>
        </tr>
    </table>
</div>

@if ($bankAccount->description)
    <div class="alert alert-info"><strong>{{ __('app.description') }}:</strong><br>{{ $bankAccount->description }}</div>
@endif

@endsection
