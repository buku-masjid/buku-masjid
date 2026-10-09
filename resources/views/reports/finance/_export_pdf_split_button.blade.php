<div class="btn-group" role="group">
    <a href="{{ route($pdfRoute, $pdfParams) }}" class="btn">{{ __('report.export_pdf') }}</a>
    <button type="button" class="btn dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route($pdfRoute, $pdfParams + ['paper_format' => 'A4']) }}">{{ __('report.paper_format_a4') }}</a>
        <a class="dropdown-item" href="{{ route($pdfRoute, $pdfParams + ['paper_format' => 'Legal']) }}">{{ __('report.paper_format_legal') }}</a>
    </div>
</div>
