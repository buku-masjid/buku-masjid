@if (request('action') == 'create_bank_account_balance')
@can('update', $bankAccount)
    <div id="bankAccountBalanceModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account_balance.create') }}</h5>
                    {{ link_to_route('bank_accounts.show', '', [$bankAccount], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['bank_accounts.balances.store', $bankAccount], 'autocomplete' => 'off']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">{!! FormField::text('date', ['required' => true, 'label' => __('bank_account_balance.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-8">{!! FormField::price('amount', ['required' => true, 'label' => __('bank_account_balance.amount'), 'type' => 'number']) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('app.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('bank_account_balance.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('bank_accounts.show', __('app.cancel'), [$bankAccount], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit_bank_account_balance' && $editableBankAccountBalance)
@can('update', $bankAccount)
    <div id="bankAccountBalanceModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account_balance.edit') }}</h5>
                    {{ link_to_route('bank_accounts.show', '', [$bankAccount], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableBankAccountBalance, ['route' => ['bank_accounts.balances.update', $bankAccount, $editableBankAccountBalance], 'method' => 'patch', 'autocomplete' => 'off']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">{!! FormField::text('date', ['required' => true, 'label' => __('bank_account_balance.date'), 'class' => 'date-select']) !!}</div>
                        <div class="col-md-8">{!! FormField::price('amount', ['required' => true, 'label' => __('bank_account_balance.amount'), 'type' => 'number']) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('app.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('bank_account_balance.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('bank_accounts.show', __('app.cancel'), [$bankAccount], ['class' => 'btn btn-secondary']) }}
                    {!! link_to_route(
                        'bank_accounts.show',
                        __('app.delete'),
                        [$bankAccount, 'action' => 'delete_bank_account_balance', 'bank_account_balance_id' => $editableBankAccountBalance->id],
                        ['id' => 'delete-bank_account_balance-'.$editableBankAccountBalance->id, 'class' => 'btn btn-danger float-left']
                    ) !!}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'delete_bank_account_balance' && $editableBankAccountBalance)
@can('update', $bankAccount)
    <div id="bankAccountBalanceModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account_balance.delete') }}</h5>
                    {{ link_to_route('bank_accounts.show', '', [$bankAccount], ['class' => 'close']) }}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">{{ __('bank_account_balance.date') }}</label>
                            <p>{{ $editableBankAccountBalance->date }}</p>
                            <label class="control-label">{{ __('bank_account_balance.amount') }}</label>
                            <p>{{ $editableBankAccountBalance->amount_string }}</p>
                            <label class="control-label">{{ __('app.description') }}</label>
                            <p>{{ $editableBankAccountBalance->description }}</p>
                        </div>
                    </div>
                    {!! $errors->first('bank_account_balance_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">{{ __('app.delete_confirm') }}</div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['bank_accounts.balances.update', $bankAccount, $editableBankAccountBalance], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class'=>'btn btn-danger', 'id' => 'delete-bank_account_balance-'.$editableBankAccountBalance->id],
                        [
                            'bank_account_balance_id' => $editableBankAccountBalance->id
                        ]
                    ) !!}
                    {{ link_to_route('bank_accounts.show', __('app.cancel'), [$bankAccount], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        </div>
    </div>
@endcan
@endif
