@if (request('action') == 'create')
    @can('create', new App\Models\BankAccount)
    <div id="bankAccountModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account.create') }}</h5>
                    {{ link_to_route('bank_accounts.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'bank_accounts.store']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('bank_account.name')]) !!}
                    {!! FormField::text('number', ['required' => true, 'label' => __('bank_account.number')]) !!}
                    {!! FormField::text('account_name', ['required' => true, 'label' => __('bank_account.account_name')]) !!}
                    {!! FormField::textarea('description', ['label' => __('bank_account.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('bank_account.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('bank_accounts.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif

@if (request('action') == 'edit' && $editableBankAccount)
    @can('update', $editableBankAccount)
    <div id="bankAccountModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account.edit') }}</h5>
                    {{ link_to_route('bank_accounts.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableBankAccount, ['route' => ['bank_accounts.update', $editableBankAccount], 'method' => 'patch']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('bank_account.name')]) !!}
                    {!! FormField::text('number', ['required' => true, 'label' => __('bank_account.number')]) !!}
                    {!! FormField::text('account_name', ['required' => true, 'label' => __('bank_account.account_name')]) !!}
                    {!! FormField::textarea('description', ['label' => __('bank_account.description')]) !!}
                    {!! FormField::radios('is_active', [__('app.inactive'), __('app.active')], ['label' => __('app.status')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('bank_account.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('bank_accounts.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableBankAccount)
                        {!! link_to_route(
                            'bank_accounts.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editableBankAccount->id],
                            ['id' => 'del-bank_account-'.$editableBankAccount->id, 'class' => 'btn btn-danger float-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif

@if (request('action') == 'delete' && $editableBankAccount)
    @can('delete', $editableBankAccount)
    <div id="bankAccountModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('bank_account.delete') }} {{ $editableBankAccount->type }}</h5>
                    {{ link_to_route('bank_accounts.index', '', [], ['class' => 'close']) }}
                </div>
                <div class="modal-body">
                    <label class="control-label">{{ __('bank_account.name') }}</label>
                    <p>{{ $editableBankAccount->name }}</p>
                    <label class="control-label">{{ __('bank_account.number') }}</label>
                    <p>{{ $editableBankAccount->number }}</p>
                    <label class="control-label">{{ __('bank_account.account_name') }}</label>
                    <p>{{ $editableBankAccount->account_name }}</p>
                    <label class="control-label">{{ __('bank_account.description') }}</label>
                    <p>{{ $editableBankAccount->description }}</p>
                    {!! $errors->first('bank_account_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">{{ __('app.delete_confirm') }}</div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['bank_accounts.destroy', $editableBankAccount], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class'=>'btn btn-danger'],
                        ['bank_account_id' => $editableBankAccount->id]
                    ) !!}
                    {{ link_to_route('bank_accounts.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        </div>
    </div>
    @endcan
@endif
