@extends('layouts.settings')

@section('title', __('category.transactions'))

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $category->name }}</h1>
    <div class="page-subtitle">{{ __('category.transactions') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('categories.index', __('category.back_to_index'), [], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>

@include('transactions.partials.stats')

@if ($category->description)
    <div class="alert alert-info"><strong>{{ __('app.description') }}:</strong><br>{{ $category->description }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <div class="card-header">
                @include('categories.partials.show_filter')
            </div>
            @desktop
            <table class="table table-sm table-responsive-sm table-hover table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="text-center col-md-1">{{ __('app.table_no') }}</th>
                        <th class="text-center col-md-1">{{ __('app.date') }}</th>
                        <th class="col-md-6">{{ __('transaction.description') }}</th>
                        <th class="text-right col-md-2">{{ __('transaction.amount') }}</th>
                        <th class="text-center col-md-2">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-center">{{ $transaction->date }}</td>
                        <td>
                            <span class="float-right">
                                @if ($transaction->partner)
                                    @php
                                        $partnerRoute = route('partners.show', [
                                            $transaction->partner_id,
                                            'start_date' => $transaction->date,
                                            'end_date' => $transaction->date,
                                        ]);
                                    @endphp
                                    <a class="badge badge-info" href="{{ $partnerRoute }}">{{ $transaction->partner->name }}</a>
                                @endif
                                <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
                                    {{ $transaction->bankAccount->name }}
                                </span>
                            </span>
                            <div style="max-width: 600px" class="mr-3">{!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}</div>
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td class="text-center">
                            @can('update', $transaction)
                                @can('manage-transactions', auth()->activeBook())
                                    {!! link_to_route(
                                        'transactions.edit',
                                        __('app.edit'),
                                        [$transaction, 'reference_page' => 'category', 'category_id' => $category->id, 'start_date' => $startDate, 'end_date' => $endDate] + request(['query']),
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
                    @include('categories.partials.single_transaction_mobile', ['transaction' => $transaction])
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
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1
    });
})();
</script>
@endpush
