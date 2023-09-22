@can('create', new App\Transaction)
@if (request('action') == 'add-income')
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('transaction.add_income') }}</h5>
                    {{ link_to_route('transactions.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'transactions.store', 'autocomplete' => 'off']) !!}
                {{ Form::hidden('in_out', 1) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('masjid.currency_code')]) !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('transaction.add_income'), ['class' => 'btn btn-success']) !!}
                    {{ Form::hidden('book_id', auth()->activeBookId()) }}
                    {{ link_to_route('transactions.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif
@if (request('action') == 'add-spending')
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('transaction.add_spending') }}</h5>
                    {{ link_to_route('transactions.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'transactions.store', 'autocomplete' => 'off']) !!}
                {{ Form::hidden('in_out', 0) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('masjid.currency_code')]) !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('transaction.add_spending'), ['class' => 'btn btn-danger']) !!}
                    {{ Form::hidden('book_id', auth()->activeBookId()) }}
                    {{ link_to_route('transactions.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif
@endcan

@if (request('action') == 'edit' && $editableTransaction)
@can('update', $editableTransaction)
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('transaction.edit') }}</h5>
                    {{ link_to_route('transactions.index', '', ['month' => $month, 'year' => $year], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableTransaction, ['route' => ['transactions.update', $editableTransaction], 'method' => 'patch', 'autocomplete' => 'off']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('transaction.amount'), 'type' => 'number', 'currency' => config('masjid.currency_code')]) !!}</div>
                        <div class="col-md-6">{!! FormField::radios('in_out', [__('transaction.spending'), __('transaction.income')], ['required' => true, 'label' => __('transaction.transaction'), 'list_style' => 'unstyled']) !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('transaction.update'), ['class' => 'btn btn-success']) !!}
                    {{ Form::hidden('query', request('query')) }}
                    {{ Form::hidden('queried_category_id', request('category_id')) }}
                    {{ link_to_route('transactions.index', __('app.cancel'), ['month' => $month, 'year' => $year], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableTransaction)
                        {!! link_to_route(
                            'transactions.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editableTransaction->id] + Request::only('page', 'month', 'year'),
                            ['id' => 'del-transaction-'.$editableTransaction->id, 'class' => 'btn btn-danger float-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'delete' && $editableTransaction)
@can('delete', $editableTransaction)
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.delete') }} {{ $editableTransaction->type }}</h5>
                    {{ link_to_route('transactions.index', '', ['date' => $editableTransaction->date], ['class' => 'close']) }}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">{{ __('app.date') }}</label>
                            <p>{{ $editableTransaction->date }}</p>
                            <label class="control-label">{{ __('transaction.amount') }}</label>
                            <p>{{ $editableTransaction->amount_string }}</p>
                            <label class="control-label">{{ __('transaction.description') }}</label>
                            <p>{{ $editableTransaction->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">{{ __('category.category') }}</label>
                            <p>{{ optional($editableTransaction->category)->name }}</p>
                        </div>
                    </div>
                    {!! $errors->first('transaction_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">{{ __('app.delete_confirm') }}</div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['transactions.destroy', $editableTransaction], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class'=>'btn btn-danger'],
                        [
                            'transaction_id' => $editableTransaction->id,
                            'month' => $editableTransaction->month, 'year' => $editableTransaction->year,
                        ]
                    ) !!}
                    {{ link_to_route('transactions.index', __('app.cancel'), ['month' => $editableTransaction->month, 'year' => $editableTransaction->year], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        </div>
    </div>
@endcan
@endif
