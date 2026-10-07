<div class="list-group list-group-horizontal-md mb-3">
    <a href="{{ route('public_reports.index', Request::all()) }}" class="list-group-item list-group-item-action border border-teal border-opacity-25 rounded-3 mt-1 me-2 p-2 {{ in_array(Request::segment(2), [null]) ? 'bm-bg-primary-soft bm-txt-primary border-bm-bg-primary' : '' }}">
        <i class="ti ti-home" style="line-height: 22px"></i>&nbsp;{{ __('report.report') }}
    </a>
    <a href="{{ route('public_reports.finance.summary', Request::all()) }}" class="list-group-item list-group-item-action border border-teal border-opacity-25 rounded-3 mt-1 me-2 p-2 {{ in_array(Request::segment(2), ['ringkasan']) ? 'bm-bg-primary-soft bm-txt-primary border-bm-bg-primary' : '' }}">
        <i class="ti ti-table" style="line-height: 22px"></i>&nbsp;{{ __('report.'.$reportPeriode) }}
    </a>
    <a href="{{ route('public_reports.finance.categorized', Request::all()) }}" class="list-group-item list-group-item-action border border-teal border-opacity-25 rounded-3 mt-1 me-2 p-2 {{ Request::segment(2) == 'per_kategori' ? 'bm-bg-primary-soft bm-txt-primary border-bm-bg-primary' : '' }}">
        <i class="ti ti-package" style="line-height: 22px"></i>&nbsp;{{ __('report.finance_categorized') }}
    </a>
    <a href="{{ route('public_reports.finance.detailed', Request::all()) }}" class="list-group-item list-group-item-action border border-teal border-opacity-25 rounded-3 mt-1 p-2 {{ Request::segment(2) == 'rincian' ? 'bm-bg-primary-soft bm-txt-primary border-bm-bg-primary' : '' }}">
        <i class="ti ti-alert-circle" style="line-height: 22px"></i>&nbsp;{{ __('report.finance_detailed') }}
    </a>
</div>
