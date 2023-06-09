@extends('layouts.app')

@section('title', __('loan.list'))

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ __('loan.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $loans->total() }} {{ __('loan.loan') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('loans.create', __('loan.create'), [], ['class' => 'btn btn-success']) }}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <div class="card-header d-block d-sm-flex">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('loan.search'), 'placeholder' => __('loan.search_text'), 'class' => 'form-control-sm mx-sm-2']) !!}
                {{ Form::hidden('type_id', request('type_id')) }}
                <div class="form-group">
                    {{ Form::submit(__('loan.search'), ['class' => 'btn btn-info btn-sm mr-2']) }}
                    {{ link_to_route('loans.index', __('app.reset'), request(['type_id']), ['class' => 'btn btn-secondary btn-sm mr-2']) }}
                </div>
                {{ Form::close() }}
                <div class="card-options">
                    <div class="btn-group">
                        {{ link_to_route('loans.index', __('loan.all'), ['type_id' => null] + request(['q']), ['class' => 'btn btn-sm '.(request('type_id') == null ? 'btn-info active' : 'btn-secondary')]) }}
                        {{ link_to_route('loans.index', __('loan.types.debt'), ['type_id' => 1] + request(['q']), ['class' => 'btn btn-sm '.(request('type_id') == '1' ? 'btn-info active' : 'btn-secondary')]) }}
                        {{ link_to_route('loans.index', __('loan.types.receivable'), ['type_id' => 2] + request(['q']), ['class' => 'btn btn-sm '.(request('type_id') === '2' ? 'btn-info active' : 'btn-secondary')]) }}
                    </div>
                </div>
            </div>
            <table class="table table-sm table-responsive-sm table-hover table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('loan.partner') }}</th>
                        <th>{{ __('loan.type') }}</th>
                        <th class="text-right">{{ __('loan.amount') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th class="text-center">{{ __('loan.start_date') }}</th>
                        <th class="text-center">{{ __('loan.end_date') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $key => $loan)
                    <tr>
                        <td class="text-center">{{ $loans->firstItem() + $key }}</td>
                        <td>{{ $loan->partner->name }}</td>
                        <td>{{ $loan->type }}</td>
                        <td class="text-right">{{ $loan->amount_string }}</td>
                        <td>{{ $loan->description }}</td>
                        <td class="text-center">{{ $loan->start_date }}</td>
                        <td class="text-center">{{ $loan->end_date }}</td>
                        <td class="text-center">
                            @can('view', $loan)
                                {{ link_to_route(
                                    'loans.show',
                                    __('app.show'),
                                    [$loan],
                                    ['class' => 'btn btn-secondary btn-sm', 'id' => 'show-loan-' . $loan->id]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $loans->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
