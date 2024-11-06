@extends('layouts.settings')

@section('title', __('bank_account.list'))

@section('content_settings')
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

<div class="row">
    @forelse ($bankAccounts as $bankAccount)
        <div class="col-sm-6 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $bankAccount->name }}</h3>
                </div>
                <div class="card-body">
                    <span class="float-right">{{ $bankAccount->status }}</span>
                    <p><span class="text-primary">{{ __('bank_account.number') }}</span>:<br><strong>{{ $bankAccount->number }}</strong></p>
                    <p><span class="text-primary">{{ __('bank_account.account_name') }}</span>:<br><strong>{{ $bankAccount->account_name }}</strong></p>
                    @if ($bankAccount->description)
                    <p><span class="text-primary">{{ __('app.description') }}</span>:<br>{{ $bankAccount->description }}</p>
                    @endif
                    <p><span class="text-primary">{{ __('bank_account_balance.amount') }}</span>:<br><strong>Rp. {{ optional($bankAccount->lastBalance)->amount_string }}</strong></p>
                    <p><span class="text-primary">{{ __('bank_account_balance.date') }}</span>:<br><strong>{{ optional($bankAccount->lastBalance)->date }}</strong></p>
                </div>
                <div class="card-footer">
                    @can('view', $bankAccount)
                        {{ link_to_route(
                            'bank_accounts.show',
                            __('app.show'),
                            $bankAccount,
                            [
                                'id' => 'show-bank_account-'.$bankAccount->id,
                                'class' => 'btn btn-secondary',
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
                                'class' => 'btn btn-warning',
                            ]
                        ) }}
                    @endcan
                </div>
            </div>
        </div>
    @empty
        {{ __('bank_account.empty') }}
    @endforelse
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
