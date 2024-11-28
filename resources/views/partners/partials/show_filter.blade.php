{{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
    {!! FormField::text('query', [
        'value' => request('query'), 'label' => false,
        'class' => 'form-control-sm mr-2', 'placeholder' => __('transaction.search_text'),
    ]) !!}
    {!! FormField::text('start_date', [
        'value' => request('start_date'), 'label' => false, 'value' => $startDate,
        'class' => 'form-control-sm mr-2 date-select', 'placeholder' => __('time.start_date'),
    ]) !!}
    {!! FormField::text('end_date', [
        'value' => request('end_date'), 'label' => false, 'value' => $endDate,
        'class' => 'form-control-sm mr-2 date-select', 'placeholder' => __('time.end_date'),
    ]) !!}
    {!! FormField::select('book_id', $availableBooks, [
        'value' => request('book_id'), 'label' => false,
        'class' => 'form-control-sm mr-2', 'placeholder' => __('book.all'),
    ]) !!}
    <div class="form-group mb-3">
        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm mr-2']) }}
        {{ link_to_route('partners.show', __('app.reset'), $partner, ['class' => 'btn btn-secondary btn-sm mr-2']) }}
    </div>
{{ Form::close() }}
