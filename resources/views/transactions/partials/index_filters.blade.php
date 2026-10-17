{{ Form::open(['method' => 'get']) }}
<div class="row g-2 mb-3">
    <div class="col-auto">
        <label for="q" class="form-label mt-2 mb-0">{{ __('app.filter') }}</label>
    </div>
    <div class="col-auto">
        {!! Form::text('query', request('query'), ['class' => 'form-control ', 'placeholder' => __('transaction.search_text'), 'style' => 'width:260px']) !!}
    </div>
    <div class="col-auto">
        {{ Form::select('date', get_dates(), $date, ['class' => 'form-control ', 'placeholder' => '--']) }}
    </div>
    <div class="col-auto">
        {{ Form::select('month', get_months(), $month, ['class' => 'form-control ']) }}
    </div>
    <div class="col-auto">
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control ']) }}
    </div>
    <div class="col-auto">
        {{ Form::select('category_id', $categories, request('category_id'), ['placeholder' => __('category.all'), 'class' => 'form-control ', 'style' => 'width:200px']) }}
    </div>
    <div class="col-auto">
        {{ Form::select('bank_account_id', $bankAccounts, request('bank_account_id'), ['placeholder' => '-- '.__('transaction.origin_destination').' --', 'class' => 'form-control ']) }}
    </div>
    <div class="col-auto">
        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary px-2']) }}
        {{ link_to_route('transactions.index', __('app.reset'), [], ['class' => 'btn px-2']) }}
        @livewire('prev-month-button', ['routeName' => 'transactions.index', 'buttonClass' => 'btn px-2'])
        @livewire('next-month-button', ['routeName' => 'transactions.index', 'buttonClass' => 'btn px-2'])
    </div>
</div>
{{ Form::close() }}
