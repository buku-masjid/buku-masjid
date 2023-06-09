@extends('layouts.app')

@section('title', __('report.report').' - '.$year)

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ __('report.graph') }} {{ $year }}</h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('year', __('report.view_yearly_label'), ['class' => 'control-label mr-2']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-2']) }}
        {{ Form::select('partner_id', $partners, $partnerId, ['class' => 'form-control mr-2', 'placeholder' => '-- '.__('partner.all').' --']) }}
        {{ Form::select('category_id', $categories, $categoryId, ['class' => 'form-control mr-2', 'placeholder' => '-- '.__('category.all').' --']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-2']) }}
            {{ link_to_route('reports.index', __('report.this_year'), [], ['class' => 'btn btn-secondary mr-2']) }}
        </div>
        {{ Form::hidden('format', request('format', 'in_months')) }}
        {{ Form::close() }}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <strong>{{ auth()->user()->currency_code }}</strong>
        <div id="yearly-chart" style="height: 250px;"></div>
        <div class="text-center"><strong>{{ __('time.month') }}</strong></div>
    </div>
</div>

<div class="card table-responsive">
    <div class="card-header">
        <h3 class="card-title">{{ __('report.detail') }}</h3>
        <div class="card-options btn-group" role="group">
            {{ link_to_route('reports.index', __('report.in_months'), array_merge(request()->all(), ['format' => 'in_months']), ['class' => 'btn btn-sm '.(in_array(request('format'), ['in_months', null]) ? 'btn-info' : 'btn-secondary')]) }}
            {{ link_to_route('reports.index', __('report.in_weeks'), array_merge(request()->all(), ['format' => 'in_weeks']), ['class' => 'btn btn-sm '.(in_array(request('format'), ['in_weeks']) ? 'btn-info' : 'btn-secondary')]) }}
        </div>
    </div>
    <div class="card-body table-responsive">
        @includeWhen($reportFormat == 'in_months', 'reports.partials.yearly_in_months', compact('data'))
        @includeWhen($reportFormat == 'in_weeks', 'reports.partials.yearly_in_weeks', compact('data'))
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/morris.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/raphael.min.js')) }}
    {{ Html::script(url('js/plugins/morris.min.js')) }}
<script>
(function() {
    new Morris.Line({
        element: 'yearly-chart',
        data: {!! collect($chartData)->toJson() !!},
        xkey: "{{ in_array(request('format'), ['in_weeks']) ? 'week' : 'month' }}",
        ykeys: ['income', 'spending', 'difference'],
        labels: ["{{ __('transaction.income') }} {{ auth()->user()->currency_code }}", "{{ __('transaction.spending') }} {{ auth()->user()->currency_code }}", "{{ __('transaction.difference') }} {{ auth()->user()->currency_code }}"],
        parseTime:false,
        lineColors: ['green', 'orange', 'blue'],
        goals: [0],
        goalLineColors : ['red'],
        smooth: true,
        lineWidth: 2,
    });
})();
</script>
@endpush
