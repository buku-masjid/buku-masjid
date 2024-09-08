@extends('layouts.settings')

@section('title', $partner->type.' '.$partner->name)

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $partner->name }}</h1>
    <div class="page-subtitle">{{ __('partner.partner_type', ['type' => $partner->type]) }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('partners.index', __('partner.back_to_index', ['type' => $partner->type]), ['type_code' => $partner->type_code], ['class' => 'btn btn-secondary']) }}
    </div>
</div>

@desktop
    <div class="card table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <tr>
                <td class="col-2 text-center">{{ __('partner.name') }}</td>
                <td class="col-2 text-center">{{ __('partner.phone') }}</td>
                <td class="col-2 text-center">{{ __('app.gender') }}</td>
                <td class="col-2 text-center">{{ __('partner.level') }}</td>
                <td class="col-2 text-center">{{ __('partner.work') }}</td>
                <td class="col-2 text-center">{{ __('app.status') }}</td>
            </tr>
            <tr>
                <td class="text-center lead" style="border-top: none;">{{ $partner->name }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->phone ? link_to('tel:'.$partner->phone, $partner->phone) : '' }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->gender }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->level }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->work }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->status }}</td>
            </tr>
        </table>
    </div>
@elsedesktop
    <div class="card table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <tr><td class="col-4">{{ __('partner.name') }}</td><td>{{ $partner->name }}</td></tr>
            <tr><td>{{ __('partner.phone') }}</td><td>{{ $partner->phone ? link_to('tel:'.$partner->phone, $partner->phone) : '' }}</td></tr>
            <tr><td>{{ __('app.gender') }}</td><td>{{ $partner->gender }}</td></tr>
            <tr><td>{{ __('partner.level') }}</td><td>{{ $partner->level }}</td></tr>
            <tr><td>{{ __('partner.work') }}</td><td>{{ $partner->work }}</td></tr>
            <tr><td>{{ __('app.status') }}</td><td>{{ $partner->status }}</td></tr>
        </table>
    </div>
@enddesktop

@if ($partner->address)
    <div class="alert alert-warning"><strong>{{ __('partner.address') }}:</strong><br>{{ $partner->address }}</div>
@endif

@if ($partner->description)
    <div class="alert alert-info"><strong>{{ __('partner.description') }}:</strong><br>{{ $partner->description }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <div class="card-header">
                @include('partners.partials.show_filter')
            </div>
            @desktop
            <table class="table table-sm table-responsive-sm table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="text-center col-md-1">{{ __('app.table_no') }}</th>
                        <th class="text-center col-md-2">{{ __('app.date') }}</th>
                        <th class="col-md-5">{{ __('transaction.description') }}</th>
                        <th class="text-right col-md-2">{{ __('transaction.amount') }}</th>
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
                            {{ $transaction->description }}
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $transaction)
                                @can('manage-transactions', auth()->activeBook())
                                    {!! link_to_route(
                                        'transactions.edit',
                                        __('app.edit'),
                                        [$transaction, 'reference_page' => 'partner', 'partner_id' => $partner->id] + request(['start_date', 'end_date', 'query']),
                                        ['id' => 'edit-transaction-'.$transaction->id]
                                    ) !!} |
                                @endcan
                            @endcan
                            {{ link_to_route('transactions.show', __('app.detail'), $transaction) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">{{ __('transaction.not_found') }}</td></tr>
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
                    </tr>
                </tfoot>
            </table>
            @elsedesktop
            <div class="card-body">
                @foreach ($transactions as $transaction)
                    @include('partners.partials.single_transaction_mobile', ['transaction' => $transaction])
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
