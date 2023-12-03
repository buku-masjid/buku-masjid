<div id="reportModal" class="modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('book.change_report_title') }}</h5>
                {{ link_to_route('reports.finance.'.$reportType, '', [], ['class' => 'close']) }}
            </div>
            {!! Form::open(['route' => ['books.report_titles.update', request('book_id')] + request()->except(['action']), 'method' => 'patch']) !!}
            <div class="modal-body">
                @php
                    if (isset(auth()->activeBook()->report_titles['finance_'.$reportType])) {
                        $existingReportTitle = auth()->activeBook()->report_titles['finance_'.$reportType];
                    }
                    $reportTitle = old('report_titles[finance_'.$reportType.']', $existingReportTitle);
                @endphp
                {{ Form::text('report_titles[finance_'.$reportType.']', $reportTitle, [
                    'required' => true,
                    'class' => 'form-control',
                ]) }}
                {{ Form::hidden('book_id', request('book_id')) }}
                {{ Form::hidden('nonce', request('nonce')) }}
            </div>
            <div class="modal-footer">
                {!! Form::submit(__('book.change_report_title'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('reports.finance.'.$reportType, __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                {!! Form::submit(__('book.reset_report_title'), ['class' => 'btn btn-secondary', 'name' => 'reset_report_title[finance_'.$reportType.']']) !!}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
