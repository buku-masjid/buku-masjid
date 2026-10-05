<div class="btn-group mr-1">
    <a href="{{ route($pdfRoute, $pdfParams) }}" class="btn btn-secondary">{{ __('report.export_pdf') }}</a>
    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route($pdfRoute, $pdfParams + ['paper_format' => 'A4']) }}">{{ __('report.paper_format_a4') }}</a>
        <a class="dropdown-item" href="{{ route($pdfRoute, $pdfParams + ['paper_format' => 'Legal']) }}">{{ __('report.paper_format_legal') }}</a>
    </div>
</div>
