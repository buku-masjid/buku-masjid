@extends('layouts.app')

@section('title', __('transaction.detail').' #'.$transaction->id)

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ __('transaction.transaction') }} #{{ $transaction->id }}</h1>
    <div class="page-subtitle">{{ __('transaction.detail') }}</div>
    <div class="page-options">
        @can('create', new App\Transaction)
            {{ link_to_route(
                'transactions.create',
                __('transaction.duplicate'),
                [
                    'action' => $transaction->in_out ? 'add-income' : 'add-spending',
                    'month' => $transaction->month,
                    'original_transaction_id' => $transaction->id,
                    'year' => $transaction->year,
                ],
                ['class' => 'btn btn-success mr-2 mt-2 mt-lg-0', 'id' => 'duplicate-transaction-'.$transaction->id]
            ) }}
        @endcan
        @if ($transaction->in_out == 0)
            {{ link_to_route(
                'transactions.print_spending_request',
                __('transaction.print_spending_request'),
                $transaction,
                ['class' => 'btn btn-secondary mr-2 mt-2 mt-lg-0']
            ) }}
        @endif
        {{ link_to_route(
            'transactions.print_receipt',
            __('transaction.print_receipt'),
            $transaction,
            ['class' => 'btn btn-secondary mr-2 mt-2 mt-lg-0']
        ) }}
        @can('update', $transaction)
            @can('manage-transactions', auth()->activeBook())
                {!! link_to_route(
                    'transactions.edit',
                    __('transaction.edit'),
                    $transaction,
                    ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'btn btn-warning text-dark mr-2 mt-2 mt-lg-0']
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
            ['class' => 'btn btn-secondary mt-2 mt-lg-0']
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
                            {!! $transaction->date_alert !!}
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
                    <tr><td>{{ __('app.description') }}</td><td>{!! nl2br(htmlentities($transaction->description)) !!}</td></tr>
                    <tr><td>{{ __('app.created_by') }}</td><td>{{ $transaction->creator->name }}</td></tr>
                    <tr><td>{{ __('app.created_at') }}</td><td>{{ $transaction->created_at }}</td></tr>
                    <tr><td>{{ __('app.updated_at') }}</td><td>{{ $transaction->updated_at }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            @if ($isDiskFull)
                <div class="alert alert-warning m-0 p-2" role="alert">{{ __('transaction.disk_is_full') }}</div>
            @endif
            <div class="card-header">
                <h3 class="card-title">
                    {{ __('transaction.files') }}
                    @if (!$transaction->files->isEmpty())
                        ({{ $transaction->files->count() }})
                    @endif
                </h3>
                <div class="card-options">
                    @can('update', $transaction)
                        @can('manage-transactions', auth()->activeBook())
                            {!! link_to_route(
                                'transactions.show',
                                __('transaction.upload_files'),
                                [$transaction, 'action' => 'upload_files'],
                                [
                                    'id' => 'upload_files-transaction-'.$transaction->id,
                                    'class' => 'btn btn-success mr-2'. ($isDiskFull ? ' disabled' : ''),
                                    'aria-disabled' => $isDiskFull ? 'true' : null,
                                    'onclick' => $isDiskFull ? 'return false;' : null,
                                ]
                            ) !!}
                        @endcan
                    @endcan
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($transaction->files as $file)
                @if (in_array($file->type_code, ['raw_image', 'image']))
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4 text-center">
                                    <a href="{{ asset('storage/'.$file->file_path) }}">
                                        <img src="{{ asset('storage/'.$file->file_path) }}" alt="{{ $file->title }}" class="img-fluid">
                                    </a>
                                </div>
                                @if ($file->title)
                                    <h4 class="card-title mb-2"><a href="javascript:void(0)">{{ $file->title }}</a></h4>
                                @endif
                                <div class="card-subtitle mt-3">{{ $file->description }}</div>
                                @can('update', $transaction)
                                    @can('manage-transactions', auth()->activeBook())
                                        <div class="mt-5 d-flex align-items-center">
                                            <div>
                                                {!! FormField::delete(
                                                    ['route' => ['transactions.files.destroy', [$transaction, $file->id]], 'onsubmit' => __('app.delete_confirm')],
                                                    '<i class="fe fe-trash-2"></i> '.__('app.delete'),
                                                    ['class' => 'btn btn-danger btn-sm', 'id' => 'delete-file-'.$file->id],
                                                    ['file_id' => $file->id]
                                                ) !!}
                                            </div>

                                            <div class="ml-auto">
                                                <a href="{{ route('transactions.show', [$transaction, 'action' => 'edit_file','file_id' => $file->id]) }}"
                                                    id="edit-file-{{ $file->id }}"
                                                    class="btn btn-warning btn-sm text-dark">
                                                    <i class="fe fe-edit"></i> {{ __('app.edit') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endcan
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

@if(Request::has('action'))
    @include('transactions._show_forms')
@endif
@endsection

@push('scripts')
<script>
(function () {
    $('#transactionModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
