<h3 class="page-title mb-3">{{ __('report.report') }}</h3>

<nav class="nav nav-vertical nav-pills me-lg-2 text-uppercase">
    <a href="{{ route('reports.finance.dashboard', Request::all()) }}" class="nav-link {{ in_array(Request::segment(3),['dashboard', null]) ? 'active' : '' }}">
        <i class="ti ti-database"></i>&nbsp;{{ __('dashboard.dashboard') }}
    </a>
    <a href="{{ route('reports.finance.summary', Request::all()) }}" class="nav-link {{ Request::segment(3) == 'summary' ? 'active' : '' }}">
        <i class="ti ti-home"></i>&nbsp;{{ __('report.'.auth()->activeBook()->report_periode_code) }}
    </a>
    <a href="{{ route('reports.finance.categorized', Request::all()) }}" class="nav-link {{ Request::segment(3) == 'categorized' ? 'active' : '' }}">
        <i class="ti ti-package"></i>&nbsp;{{ __('report.finance_categorized') }}
    </a>
    <a href="{{ route('reports.finance.detailed', Request::all()) }}" class="nav-link {{ Request::segment(3) == 'detailed' ? 'active' : '' }}">
        <i class="ti ti-alert-circle"></i>&nbsp;{{ __('report.finance_detailed') }}
    </a>
</nav>

<hr class="my-1">

<nav class="nav nav-vertical nav-pills me-lg-2 text-uppercase">
    <div class="nav-link">
        <span class="icon me-2"><i class="ti ti-settings"></i></span>{{ __('report.periode') }}
        <span class="ms-auto badge bg-primary text-primary-fg">{{ __('report.'.auth()->activeBook()->report_periode_code) }}</span>
    </div>
    <div class="nav-link">
        <span class="icon me-2"><i class="ti ti-settings"></i></span>{{ __('report.start_week_day') }}
        <span class="ms-auto badge bg-warning text-warning-fg">{{ __('time.days.'.auth()->activeBook()->start_week_day_code) }}</span>
    </div>
</nav>
