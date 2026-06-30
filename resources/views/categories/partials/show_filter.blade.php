{{ Form::open(['method' => 'get']) }}
<div class="row g-2">
    <div class="col-auto">
        {!! FormField::text('query', [
            'value' => request('query'), 'label' => false,
            'class' => 'form-control-sm me-2', 'placeholder' => __('transaction.search_text'),
        ]) !!}
    </div>
    <div class="col-auto">
        {!! FormField::text('start_date', [
            'value' => request('start_date'), 'label' => false, 'value' => $startDate,
            'class' => 'form-control-sm me-2 date-select', 'placeholder' => __('time.start_date'),
        ]) !!}
    </div>
    <div class="col-auto">
        {!! FormField::text('end_date', [
            'value' => request('end_date'), 'label' => false, 'value' => $endDate,
            'class' => 'form-control-sm me-2 date-select', 'placeholder' => __('time.end_date'),
        ]) !!}
    </div>
    <div class="col-auto">
        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm me-2']) }}
        {{ link_to_route('categories.show', __('app.reset'), $category, ['class' => 'btn btn-sm me-2']) }}
        {{ link_to_route('transactions.exports.by_category', __('transaction.download'), [$category] + request()->all(), ['class' => 'btn btn-info btn-sm']) }}
    </div>
</div>
{{ Form::close() }}
