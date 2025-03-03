{{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
<div class="btn-toolbar d-flex d-sm-block justify-content-center row" role="toolbar">
    @if ($selectedBook->report_periode_code == 'in_months')
        <div class="btn-group col col-sm-auto px-0 d-none d-sm-inline-flex" role="group">
            {{ link_to_route(Route::currentRouteName(), __('report.this_month'), Request::except(['year', 'month']), ['class' => 'btn btn-light border bm-btn mr-1']) }}
        </div>
        <div class="btn-group col col-sm-auto px-0" role="group">
            @livewire('prev-month-button', ['routeName' => Route::currentRouteName(), 'buttonClass' => 'btn btn-light border bm-btn', 'buttonText' => '&#8249;'])
            {{ Form::select('month', ['00' => '-- '.__('app.all').' --'] + get_months(), request('month', $startDate->format('m')), ['class' => 'form-control text-center', 'onchange' => 'submit()', 'style' => 'border-radius:0']) }}
            @livewire('next-month-button', ['routeName' => Route::currentRouteName(), 'buttonClass' => 'btn btn-light border bm-btn', 'buttonText' => '&#8250;'])
        </div>
        <div class="btn-group col col-sm-auto px-0 d-none d-sm-inline-flex" role="group">
            {{ Form::select('year', get_years(), $startDate->format('Y'), ['class' => 'form-control mr-1', 'onchange' => 'submit()']) }}
        </div>
    @endif
    @if ($selectedBook->report_periode_code == 'in_weeks')
        <div class="btn-group col-auto px-0" role="group">
            @livewire('prev-week-button', ['routeName' => Route::currentRouteName(), 'buttonClass' => 'btn btn-light border'])
        </div>
        <div class="btn-group col-auto px-0 d-none d-sm-inline-flex" role="group">
            {{ link_to_route(Route::currentRouteName(), __('report.this_week'), Request::except(['start_date', 'end_date']), ['class' => 'btn btn-light border']) }}
        </div>
        <div class="btn-group col-auto px-0" role="group">
            @livewire('next-week-button', ['routeName' => Route::currentRouteName(), 'buttonClass' => 'btn btn-light border'])
        </div>
    @endif
    @if ($selectedBook->report_periode_code == 'all_time')
        <div class="input-group col-auto px-0 d-inline-flex" role="group">
            <span class="input-group-text d-none d-sm-block">{{ __('time.date') }}</span>
            {{ Form::text('start_date', $startDate->format('Y-m-d'), ['class' => 'date-select form-control radius mr-1 px-2', 'style' => 'max-width: 100px;', 'onchange' => 'submit()']) }}
            {{ Form::text('end_date', $endDate->format('Y-m-d'), ['class' => 'date-select form-control radius mr-1 px-2', 'style' => 'max-width: 100px;', 'onchange' => 'submit()']) }}
            {{ link_to_route(Route::currentRouteName(), __('app.reset'), Request::except(['start_date', 'end_date']), ['class' => 'btn btn-light border bm-btn mr-1']) }}
        </div>
    @endif
</div>
{{ Form::hidden('active_book_id', request('active_book_id')) }}
{{ Form::hidden('nonce', request('nonce')) }}
{{ Form::close() }}
