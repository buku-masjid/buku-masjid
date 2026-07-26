{{ Form::open(['method' => 'get']) }}
<div class="row g-2">
    <div class="col-auto">
        {!! Form::text('query', request('query'), [
            'class' => 'form-control',
            'placeholder' => __('transaction.search_text'),
            'style' => 'width:300px',
        ]) !!}
    </div>
    <div class="col-auto">
        {!! Form::text('start_date', $startDate, [
            'class' => 'form-control date-select',
            'placeholder' => __('time.start_date'),
            'style' => 'width:100px',
        ]) !!}
    </div>
    <div class="col-auto">
        {!! Form::text('end_date', $endDate, [
            'class' => 'form-control date-select',
            'placeholder' => __('time.end_date'),
            'style' => 'width:100px',
        ]) !!}
    </div>
    <div class="col-auto">
        {!! FormField::select('book_id', $availableBooks, [
            'value' => request('book_id'), 'label' => false,
            'placeholder' => __('book.all'),
        ]) !!}
    </div>
    <div class="col-auto">
        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary']) }}
        {{ link_to_route('donors.show', __('app.reset'), $partner, ['class' => 'btn']) }}
    </div>
</div>
{{ Form::close() }}
