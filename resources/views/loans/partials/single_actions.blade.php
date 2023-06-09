@if (Request::get('action') == 'add_transaction')
<div id="transactionModal" class="modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('loan.add_transaction') }}</h5>
                {{ link_to_route('loans.show', '', [$loan], ['class' => 'close']) }}
            </div>
            {{ Form::open(['route' => ['loans.transactions.store', $loan], 'autocomplete' => 'off']) }}
            <div class="modal-body">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        {!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', today()->format('Y-m-d')), 'class' => 'date-select']) !!}
                    </div>
                    <div class="col-md-8">
                        {!! FormField::radios('in_out', $inOutOptions, ['required' => true, 'label' => __('transaction.transaction'), 'list_style' => 'inline', 'value' => $defaultInOutValue]) !!}
                    </div>
                </div>
                {!! FormField::textarea('description', ['required' => true, 'label' => __('app.description')]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('amount', ['required' => true, 'type' => 'number', 'min' => '0', 'label' => __('transaction.amount'), 'class' => 'text-right']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::textDisplay('partner', $loan->partner->name) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::submit(__('loan.add_transaction'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('loans.show', __('app.cancel'), [$loan], ['class' => 'btn btn-secondary']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
