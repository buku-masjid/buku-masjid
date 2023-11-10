<h4 class="page-title mb-3">{{ __('report.report') }}</h4>

<div class="list-group list-group-transparent mb-0 text-uppercase">
    <a href="{{ route('reports.in_months', Request::all()) }}" class="list-group-item list-group-item-action d-flex align-items-center {{ in_array(Request::segment(2), [null, 'in_months']) ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-home"></i></span>{{ __('report.view_monthly') }}
    </a>
    <a href="{{ route('reports.in_out', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(2) == 'in_out' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-package"></i></span>{{ __('report.view_in_out') }}
    </a>
    <a href="{{ route('reports.in_weeks', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(2) == 'in_weeks' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-alert-circle"></i></span>{{ __('report.view_weekly') }}
    </a>
    <hr style="border: 1px solid red;margin: 1em 0">
    <a href="{{ route('reports.finance.summary', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(3) == 'summary' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-home"></i></span>{{ __('report.finance_summary') }}
    </a>
    <a href="{{ route('reports.finance.categorized', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(3) == 'categorized' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-package"></i></span>{{ __('report.finance_categorized') }}
    </a>
    <a href="{{ route('reports.finance.detailed', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(3) == 'detailed' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-alert-circle"></i></span>{{ __('report.finance_detailed') }}
    </a>
</div>
