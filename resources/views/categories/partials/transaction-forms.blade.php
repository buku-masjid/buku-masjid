@if (request('action') == 'edit' && $editableTransaction)
@can('update', $editableTransaction)
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('transaction.edit') }}</h5>
                    {{ link_to_route('categories.show', '', [$category] + request(['start_date', 'end_date', 'query', 'category_id']), ['class' => 'close']) }}
                </div>
                {!! Form::model($editableTransaction, ['route' => ['transactions.update', $editableTransaction], 'method' => 'patch', 'autocomplete' => 'off']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-6">{!! FormField::select('category_id', $categories, ['label' => __('category.category'), 'placeholder' => __('category.uncategorized')]) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('transaction.description')]) !!}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group required {{ $errors->has('amount') ? 'has-error' : '' }}">
                                <label for="amount" class="control-label">{{ __('transaction.amount') }}</label>
                                <div class="input-group"><span class="input-group-addon">{{ auth()->user()->currency_code }}</span>
                                    <input class="form-control text-right" name="amount" type="number" id="amount" min="0" value="{{ isset($editableTransaction) ? round($editableTransaction->amount, 0) : old('amount') }}" required>
                                </div>
                                {!! $errors->first('amount', '<span class="help-block small">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-4">{!! FormField::radios('in_out', [__('transaction.spending'), __('transaction.income')], ['required' => true, 'label' => __('transaction.transaction')]) !!}</div>
                        <div class="col-md-4">{!! FormField::select('partner_id', $partners, ['label' => __('partner.partner'), 'placeholder' => __('partner.empty')]) !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('transaction.update'), ['class' => 'btn btn-success']) !!}
                    {{ Form::hidden('query', request('query')) }}
                    {{ Form::hidden('queried_parter_id', request('parter_id')) }}
                    {{ Form::hidden('start_date', request('start_date')) }}
                    {{ Form::hidden('end_date', request('end_date')) }}
                    {{ Form::hidden('reference_page', 'category') }}
                    {{ link_to_route('categories.show', __('app.cancel'), [$category] + request(['start_date', 'end_date', 'query', 'partner_id']), ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableTransaction)
                        {!! link_to_route(
                            'categories.show',
                            __('app.delete'),
                            [$category->id, 'action' => 'delete', 'id' => $editableTransaction->id] + request(['start_date', 'end_date', 'query', 'partner_id']),
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
                    {{ link_to_route('categories.show', '', [$category] + request(['start_date', 'end_date', 'query', 'category_id']), ['class' => 'close']) }}
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
                            <label class="control-label">{{ __('partner.partner') }}</label>
                            <p>{{ optional($editableTransaction->partner)->name }}</p>
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
                            'queried_category_id' => request('category_id'),
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date'),
                            'reference_page' => 'category',
                            'query' => request('query'),
                        ]
                    ) !!}
                    {{ link_to_route('categories.show', __('app.cancel'), [$category] + request(['start_date', 'end_date', 'query', 'category_id']), ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        </div>
    </div>
@endcan
@endif
