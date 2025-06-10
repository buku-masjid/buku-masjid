@extends('layouts.app')

@section('title', $partner->type.' '.$partner->name)

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $partner->name }}</h1>
    <div class="page-subtitle">{{ $partner->level ?: __('donor.donor') }}</div>
    <div class="page-options d-flex">
        @can('create', new App\Transaction)
            {{ link_to_route('donor_transactions.create', __('donor.add_donation'), ['partner_id' => $partner->id, 'reference_page' => 'donor'], ['class' => 'btn btn-success mr-2']) }}
        @endcan
        @can('update', $partner)
            {{ link_to_route('donors.edit', __('donor.edit'), $partner, ['class' => 'btn btn-warning text-dark mr-2', 'id' => 'edit-partner-'.$partner->id]) }}
        @endcan
        {{ link_to_route(
            'donors.search',
            __('donor.back_to_index'),
            [],
            ['class' => 'btn btn-secondary']
        ) }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">@include('donors._profile_card')</div>
    <div class="col-md-4">@include('donors._largest_transaction')</div>
    <div class="col-md-4">@include('donors._transactions_total')</div>
</div>

@if ($partner->address)
    <div class="alert alert-warning"><strong>{{ __('partner.address') }}:</strong><br>{{ $partner->address }}</div>
@endif

@if ($partner->description)
    <div class="alert alert-info"><strong>{{ __('partner.description') }}:</strong><br>{{ $partner->description }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="mb-2">
            @include('donors.partials.show_filter')
        </div>
        <div class="card table-responsive">
            @desktop
            <table class="table table-sm table-responsive-sm table-striped mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-center col-md-1">{{ __('app.date') }}</th>
                        <th class="col-md-4">{{ __('transaction.description') }}</th>
                        <th class="text-right col-md-2">{{ __('transaction.amount') }}</th>
                        <th class="col-md-3">{{ __('book.book') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-center">{{ $transaction->date }}</td>
                        <td>
                            <span class="float-right">
                                <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
                                    {{ $transaction->bankAccount->name }}
                                </span>
                            </span>
                            <div style="max-width: 600px" class="mr-3">{!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}</div>
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td>{{ $transaction->book->name }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $transaction)
                                @can('manage-transactions', auth()->activeBook())
                                    {!! link_to_route(
                                        'transactions.edit',
                                        __('app.edit'),
                                        [$transaction, 'reference_page' => 'partner', 'partner_id' => $partner->id, 'start_date' => $startDate, 'end_date' => $endDate] + request(['query']),
                                        ['id' => 'edit-transaction-'.$transaction->id]
                                    ) !!} |
                                @endcan
                            @endcan
                            {{ link_to_route('transactions.show', __('app.detail'), $transaction) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('transaction.not_found') }}</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="3" class="text-right">{{ __('app.total') }}</td>
                        <td class="text-right">
                            {{ format_number($transactions->sum(function ($transaction) {
                                return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                            })) }}
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            @elsedesktop
            <div class="card-body">
                @foreach ($transactions as $transaction)
                    @include('donors.partials.single_transaction_mobile', ['transaction' => $transaction])
                @endforeach
            </div>
            @enddesktop
        </div>
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        scrollMonth: false,
    });
})();
</script>
@endpush
