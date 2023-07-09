{{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
    {!! Form::text('query', request('query'), ['class' => 'form-control form-control-sm mr-2', 'placeholder' => __('transaction.search_text')]) !!}
    {{ Form::select('date', get_dates(), $date, ['class' => 'form-control form-control-sm mr-2', 'placeholder' => '--']) }}
    {{ Form::select('month', get_months(), $month, ['class' => 'form-control form-control-sm mr-2']) }}
    {{ Form::select('year', get_years(), $year, ['class' => 'form-control form-control-sm mr-2']) }}
    {{ Form::select('category_id', $categories, request('category_id'), ['placeholder' => __('category.all'), 'class' => 'form-control form-control-sm mr-2']) }}
    <div class="form-group mt-4 mt-sm-0">
        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm mr-2']) }}
        {{ link_to_route('transactions.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm mr-2']) }}
        @livewire('prev-month-button', ['routeName' => 'transactions.index', 'buttonClass' => 'btn btn-secondary btn-sm mr-2'])
        @livewire('next-month-button', ['routeName' => 'transactions.index', 'buttonClass' => 'btn btn-secondary btn-sm'])
    </div>
{{ Form::close() }}
