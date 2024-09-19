@extends('layouts.app')

@section('title', __('transaction.detail').' #'.$transaction->id)

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ __('transaction.transaction') }} #{{ $transaction->id }}</h1>
    <div class="page-subtitle">{{ __('transaction.detail') }}</div>
    <div class="page-options d-flex">
        @if ($transaction->in_out == 0)
            {{ link_to_route(
                'transactions.print_spending_request',
                __('transaction.print_spending_request'),
                $transaction,
                ['class' => 'btn btn-secondary mr-2']
            ) }}
        @endif
        {{ link_to_route(
            'transactions.print_receipt',
            __('transaction.print_receipt'),
            $transaction,
            ['class' => 'btn btn-secondary mr-2']
        ) }}
        @can('update', $transaction)
            @can('manage-transactions', auth()->activeBook())
                {!! link_to_route(
                    'transactions.edit',
                    __('transaction.edit'),
                    $transaction,
                    ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'btn btn-warning text-dark mr-2']
                ) !!}
            @endcan
        @endcan
        {{ link_to_route(
            'transactions.index',
            __('transaction.back_to_index'),
            [
                'year' => $transaction->year,
                'month' => $transaction->month,
            ],
            ['class' => 'btn btn-secondary']
        ) }}
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-default">
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td class="col-5">{{ __('transaction.id') }}</td><td>#{{ $transaction->id }}</td></tr>
                    <tr>
                        <td>{{ __('time.day_name') }} / {{ __('time.date') }}</td>
                        <td>
                            {{ $transaction->day_name }}, {{ $transaction->date_only.'-'.$transaction->month_name.'-'.$transaction->year }}
                        </td>
                    </tr>
                    <tr><td>{{ __('app.type') }}</td><td>{{ $transaction->type }}</td></tr>
                    <tr>
                        <td>{{ __('partner.partner') }}</td>
                        <td>
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
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('category.category') }}</td>
                        <td>
                            @if ($transaction->category)
                                @php
                                    $categoryRoute = route('categories.show', [
                                        $transaction->category_id,
                                        'start_date' => $transaction->date,
                                        'end_date' => $transaction->date,
                                    ]);
                                @endphp
                                <a href="{{ $categoryRoute }}">{!! $transaction->category->name_label !!}</a>
                            @endif
                        </td>
                    </tr>
                    <tr><td>{{ __('book.book') }}</td><td>{{ $transaction->book->name }}</td></tr>
                    <tr>
                        <td>{{ __('transaction.origin_destination') }}</td>
                        <td>
                            <span class="badge {{ $transaction->bankAccount->exists ? 'bg-purple' : 'bg-gray'}}">
                                {{ $transaction->bankAccount->name }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('transaction.amount') }}</td>
                        <td class="lead text-right">{{ config('money.currency_code') }} {{ $transaction->amount_string }}</td>
                    </tr>
                    <tr><td>{{ __('app.description') }}</td><td>{{ $transaction->description }}</td></tr>
                    <tr><td>{{ __('app.created_by') }}</td><td>{{ $transaction->creator->name }}</td></tr>
                    <tr><td>{{ __('app.created_at') }}</td><td>{{ $transaction->created_at }}</td></tr>
                    <tr><td>{{ __('app.updated_at') }}</td><td>{{ $transaction->updated_at }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
