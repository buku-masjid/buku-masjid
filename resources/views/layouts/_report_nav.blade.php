<h4 class="page-title mb-3">{{ __('report.report') }}</h4>

<div class="list-group list-group-transparent mb-0 text-uppercase">
    <a href="{{ route('reports.finance.dashboard', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ in_array(Request::segment(3),['dashboard', null]) ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-database"></i></span>{{ __('dashboard.dashboard') }}
    </a>
    <a href="{{ route('reports.finance.summary', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(3) == 'summary' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-home"></i></span>{{ __('report.'.auth()->activeBook()->report_periode_code) }}
    </a>
    <a href="{{ route('reports.finance.categorized', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(3) == 'categorized' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-package"></i></span>{{ __('report.finance_categorized') }}
    </a>
    <a href="{{ route('reports.finance.detailed', Request::all()) }}" style="padding-right: 4px;" class="list-group-item list-group-item-action d-flex align-items-center {{ Request::segment(3) == 'detailed' ? 'active' : '' }}">
        <span class="icon mr-2"><i class="fe fe-alert-circle"></i></span>{{ __('report.finance_detailed') }}
    </a>
</div>

<hr class="my-1">

<div class="list-group list-group-transparent mb-0 text-uppercase">
    <div class="list-group-item d-flex align-items-center">
        <span class="icon mr-2"><i class="fe fe-settings"></i></span>{{ __('report.periode') }}
        <span class="ml-auto badge badge-primary">{{ __('report.'.auth()->activeBook()->report_periode_code) }}</span>
    </div>
    <div class="list-group-item d-flex align-items-center">
        <span class="icon mr-2"><i class="fe fe-settings"></i></span>{{ __('report.start_week_day') }}
        <span class="ml-auto badge badge-warning text-dark">{{ __('time.days.'.auth()->activeBook()->start_week_day_code) }}</span>
    </div>
</div>
