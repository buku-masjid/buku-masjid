@extends('layouts.app')

@section('title', $partner->type.' '.$partner->name)

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $partner->name }}</h1>
    <div class="page-subtitle">{{ $partner->type }}</div>
    <div class="page-options d-flex">
        @can('update', $partner)
            @if ($availableLevels)
                {{ link_to_route(
                    'partners.show',
                    __('partner.change_levels'),
                    [$partner, 'action' => 'change_levels'],
                    ['id' => 'change_levels-'.$partner->id, 'class' => 'btn text-dark btn-secondary mr-2']
                ) }}
            @endif
            {{ link_to_route(
                'partners.edit',
                __('app.edit'),
                $partner,
                ['id' => 'edit-partner-'.$partner->id, 'class' => 'btn text-dark btn-warning mr-2']
            ) }}
        @endcan
        {{ link_to_route(
            'partners.search',
            __('partner.back_to_index'),
            [],
            ['class' => 'btn btn-secondary']
        ) }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">@include('partners._profile_card')</div>
    <div class="col-md-4">@include('partners._largest_transaction')</div>
    <div class="col-md-4">@include('partners._transactions_total')</div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="control-label text-primary">{{ __('partner.pdob') }}</label>
                <p>
                    @if ($partner->pob)
                        {{ $partner->pob }},
                    @endif
                    @if ($partner->dob)
                        {{ Carbon\Carbon::parse($partner->dob)->isoFormat('DD MMMM YYYY') }}
                    @endif
                    @if (!$partner->pob && !$partner->dob)
                        {{ __('app.unknown') }}
                    @endif
                </p>
                <label class="control-label text-primary">{{ __('address.address') }}</label>
                <p>{{ $partner->address ?: __('app.unknown') }}</p>
                <label class="control-label text-primary">{{ __('address.rt') }} / {{ __('address.rw') }}</label>
                <p>
                    @if (!$partner->rt && !$partner->rw)
                        {{ __('app.unknown') }}
                    @else
                        {{ $partner->rt ?: __('app.unknown') }} / {{ $partner->rw ?: __('app.unknown') }}
                    @endif
                </p>
            </div>
            <div class="col-md-4">
                <label class="control-label text-primary">{{ __('partner.religion') }}</label>
                <p>{{ $partner->religion }}</p>
                <label class="control-label text-primary">{{ __('partner.work_detail') }}</label>
                <p>{{ $partner->work_type }} {{ $partner->work ? '('.$partner->work.')' : '' }}</p>
            </div>
            <div class="col-md-4">
                <label class="control-label text-primary">{{ __('partner.marital_status') }}</label>
                <p>{{ $partner->marital_status }}</p>
                <label class="control-label text-primary">{{ __('partner.financial_status') }}</label>
                <p>{{ $partner->financial_status }}</p>
                <label class="control-label text-primary">{{ __('partner.activity_status') }}</label>
                <p>{{ $partner->activity_status }}</p>
            </div>
        </div>
    </div>
</div>

@if ($partner->description)
    <div class="alert alert-info"><strong>{{ __('partner.description') }}:</strong><br>{{ $partner->description }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="mb-2">
            @include('partners.partials.show_filter')
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
                    @include('partners.partials.single_transaction_mobile', ['transaction' => $transaction])
                @endforeach
            </div>
            @enddesktop
        </div>
    </div>
</div>

@if(Request::has('action'))
    @include('partners._show_forms')
@endif
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#partnerModal').modal({
        show: true,
        backdrop: 'static',
    });
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
