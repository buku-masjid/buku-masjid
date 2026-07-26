{{ Form::open(['method' => 'get']) }}
<div class="row g-2 mb-3">
    <div class="col-auto">
        {!! Form::text('query', request('query'), [
            'class' => 'form-control',
            'placeholder' => __('transaction.search_text'),
            'style' => 'width:300px',
        ]) !!}
    </div>
    <div class="col-auto">
        {!! Form::text('start_date', request('start_date', $startDate), [
            'class' => 'form-control date-select',
            'placeholder' => __('time.start_date'),
            'style' => 'width:100px',
        ]) !!}
    </div>
    <div class="col-auto">
        {!! Form::text('end_date', request('end_date', $endDate), [
            'class' => 'form-control date-select',
            'placeholder' => __('time.end_date'),
            'style' => 'width:100px',
        ]) !!}
    </div>
    <div class="col-auto">
        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary']) }}
        {{ link_to_route('categories.show', __('app.reset'), $category, ['class' => 'btn']) }}
        {{ link_to_route('transactions.exports.by_category', __('transaction.download'), [$category] + request()->all(), ['class' => 'btn btn-info']) }}
    </div>
</div>
{{ Form::close() }}
