@if (request('action') == 'create')
    @can('create', new App\Models\BankAccount)
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('bank_account.create') }}</h3></div>
        {!! Form::open(['route' => 'bank_accounts.store']) !!}
        <div class="card-body">
            {!! FormField::text('name', ['required' => true, 'label' => __('bank_account.name')]) !!}
            {!! FormField::text('number', ['required' => true, 'label' => __('bank_account.number')]) !!}
            {!! FormField::text('account_name', ['required' => true, 'label' => __('bank_account.account_name')]) !!}
            {!! FormField::textarea('description', ['label' => __('bank_account.description')]) !!}
        </div>
        <div class="card-footer">
            {!! Form::submit(__('bank_account.create'), ['class' => 'btn btn-success']) !!}
            {{ link_to_route('bank_accounts.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
        {!! Form::close() !!}
    </div>
    @endcan
@endif
@if (request('action') == 'edit' && $editableBankAccount)
    @can('update', $editableBankAccount)
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('bank_account.edit') }}</h3></div>
        {!! Form::model($editableBankAccount, ['route' => ['bank_accounts.update', $editableBankAccount->id], 'method' => 'patch']) !!}
        <div class="card-body">
            {!! FormField::text('name', ['required' => true, 'label' => __('bank_account.name')]) !!}
            {!! FormField::text('number', ['required' => true, 'label' => __('bank_account.number')]) !!}
            {!! FormField::text('account_name', ['required' => true, 'label' => __('bank_account.account_name')]) !!}
            {!! FormField::textarea('description', ['label' => __('bank_account.description')]) !!}
            {!! FormField::radios('is_active', [__('app.inactive'), __('app.active')], ['label' => __('app.status')]) !!}
        </div>
        <div class="card-footer">
            {!! Form::submit(__('bank_account.update'), ['class' => 'btn btn-success']) !!}
            {{ link_to_route('bank_accounts.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
            @can('delete', $editableBankAccount)
                {!! link_to_route(
                    'bank_accounts.index',
                    __('app.delete'),
                    ['action' => 'delete', 'id' => $bankAccount->id],
                    ['id' => 'del-bank_account-' . $bankAccount->id, 'class' => 'float-right']
                ) !!}
            @endcan
        </div>
        {!! Form::close() !!}
    </div>
    @endcan
@endif
@if (request('action') == 'delete' && $editableBankAccount)
    @can('delete', $editableBankAccount)
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('bank_account.delete') }}</h3></div>
        <div class="card-body">
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
        <div class="card-body">{{ __('app.delete_confirm') }}</div>
        <div class="card-footer">
            {!! FormField::delete(
                ['route'=>['bank_accounts.destroy', $editableBankAccount->id]],
                __('app.delete_confirm_button'),
                ['class'=>'btn btn-danger'],
                [
                    'bank_account_id' => $editableBankAccount->id,
                    'page' => request('page'),
                    'q' => request('q'),
                ]
            ) !!}
            {{ link_to_route('bank_accounts.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
    @endcan
@endif
