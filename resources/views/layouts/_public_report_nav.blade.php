<div class="list-group list-group-horizontal-md mb-3 text-uppercase">
    <a href="{{ route('public_reports.finance.summary', Request::all()) }}" class="list-group-item list-group-item-action p-2 {{ in_array(Request::segment(2), [null, 'ringkasan']) ? 'active' : '' }}">
        <i class="ti ti-home"></i>&nbsp;{{ __('report.'.$reportPeriode) }}
    </a>
    <a href="{{ route('public_reports.finance.categorized', Request::all()) }}" class="list-group-item list-group-item-action p-2 {{ Request::segment(2) == 'per_kategori' ? 'active' : '' }}">
        <i class="ti ti-package"></i>&nbsp;{{ __('report.finance_categorized') }}
    </a>
    <a href="{{ route('public_reports.finance.detailed', Request::all()) }}" class="list-group-item list-group-item-action p-2 {{ Request::segment(2) == 'rincian' ? 'active' : '' }}">
        <i class="ti ti-alert-circle"></i>&nbsp;{{ __('report.finance_detailed') }}
    </a>
</div>
