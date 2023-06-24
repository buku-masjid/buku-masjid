{{-- @can('create', new App\BankAccountBalance) --}}
@if (request('action') == 'create_bank_account_balance')
    <div id="bankAccountBalanceModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account_balance.create') }}</h5>
                    {{ link_to_route('bank_accounts.show', '', [$bankAccount], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['bank_accounts.balances.store', $bankAccount], 'autocomplete' => 'off']) !!}
                {{ Form::hidden('in_out', 1) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', date('Y-m-d')), 'class' => 'date-select']) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('bank_account_balance.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">{!! FormField::price('amount', ['required' => true, 'label' => __('bank_account_balance.amount'), 'type' => 'number']) !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('bank_account_balance.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('bank_accounts.show', __('app.cancel'), [$bankAccount], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif
{{-- @endcan --}}

@if (request('action') == 'edit_bank_account_balance' && $editableBankAccountBalance)
{{-- @can('update', $editableBankAccountBalance) --}}
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
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'class' => 'date-select']) !!}</div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('bank_account_balance.description')]) !!}
                    <div class="row">
                        <div class="col-md-4">{!! FormField::price('amount', ['required' => true, 'label' => __('bank_account_balance.amount'), 'type' => 'number']) !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('bank_account_balance.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('bank_accounts.show', __('app.cancel'), [$bankAccount], ['class' => 'btn btn-secondary']) }}
                    {{-- @can('delete', $editableBankAccountBalance) --}}
                        {!! link_to_route(
                            'bank_accounts.show',
                            __('app.delete'),
                            [$bankAccount, 'action' => 'delete_bank_account_balance', 'bank_account_balance_id' => $editableBankAccountBalance->id],
                            ['id' => 'delete-bank_account_balance-'.$editableBankAccountBalance->id, 'class' => 'btn btn-danger float-left']
                        ) !!}
                    {{-- @endcan --}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
{{-- @endcan --}}
@endif

@if (request('action') == 'delete_bank_account_balance' && $editableBankAccountBalance)
{{-- @can('delete', $editableBankAccountBalance) --}}
    <div id="bankAccountBalanceModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.delete') }} {{ $editableBankAccountBalance->date }}</h5>
                    {{ link_to_route('bank_accounts.show', '', [$bankAccount], ['class' => 'close']) }}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">{{ __('time.date') }}</label>
                            <p>{{ $editableBankAccountBalance->date }}</p>
                            <label class="control-label">{{ __('transaction.amount') }}</label>
                            <p>{{ $editableBankAccountBalance->amount }}</p>
                            <label class="control-label">{{ __('app.description') }}</label>
                            <p>{{ $editableBankAccountBalance->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">{{ __('category.category') }}</label>
                            <p>{{ optional($editableBankAccountBalance->category)->name }}</p>
                            <label class="control-label">{{ __('partner.partner') }}</label>
                            <p>{{ optional($editableBankAccountBalance->partner)->name }}</p>
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
{{-- @endcan --}}
@endif
