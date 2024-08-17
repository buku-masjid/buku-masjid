@extends('layouts.settings')

@section('title', __('book.edit'))

@section('content_settings')
<div class="row justify-content-center">
    @if (request('action') == 'delete' && $book)
    <div class="col-md-6">
        @can('delete', $book)
            <div class="page-header">
                <h1 class="page-title">{{ $book->name }}</h1>
                <div class="page-subtitle">{{ __('book.delete') }}</div>
                <div class="page-options d-flex"></div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('book.delete') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('book.name') }}</label>
                            <p>{{ $book->name }}</p>
                            <label class="control-label text-primary">{{ __('book.description') }}</label>
                            <p>{{ $book->description }}</p>
                            <label class="control-label text-primary">{{ __('bank_account.bank_account') }}</label>
                            <p>{{ optional($book->bankAccount)->name }}</p>
                            <label class="control-label text-primary">{{ __('book.budget') }}</label>
                            <p>{{ $book->budget }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-primary">{{ __('book.report_visibility') }}</label>
                            <p>{{ __('book.report_visibility_'.$book->report_visibility_code) }}</p>
                            <label class="control-label text-primary">{{ __('report.periode') }}</label>
                            <p>{{ __('report.'.$book->report_periode_code) }}</p>
                            <label class="control-label text-primary">{{ __('report.start_week_day') }}</label>
                            <p>{{ __('time.days.'.$book->start_week_day_code) }}</p>
                        </div>
                    </div>
                    {!! $errors->first('book_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body bg-warning">
                    <div class="row">
                        <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                        <div class="col-11">{!! __('book.delete_confirm') !!}</div>
                    </div>
                </div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['books.destroy', $book], 'onsubmit' => __('app.delete_confirm')],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['book_id' => $book->id]
                    ) !!}
                    {{ link_to_route('books.edit', __('app.cancel'), [$book], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
    </div>
    @else
    <div class="col-md-10">
        <div class="page-header">
            <h1 class="page-title">{{ $book->name }}</h1>
            <div class="page-subtitle">{{ __('book.edit') }}</div>
            <div class="page-options d-flex">
                {{ link_to_route('books.show', __('app.cancel'), [$book], ['class' => 'btn btn-secondary float-right']) }}
            </div>
        </div>
        <div class="card">
            {{ Form::model($book, ['route' => ['books.update', $book], 'method' => 'patch']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-primary">{{ __('book.detail') }}</h4>
                        {!! FormField::text('name', ['required' => true, 'label' => __('book.name')]) !!}
                        {!! FormField::textarea('description', ['label' => __('book.description')]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                {!! FormField::select('bank_account_id', $bankAccounts, [
                                    'label' => __('bank_account.bank_account'),
                                    'placeholder' => __('book.no_bank_account'),
                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! FormField::price('budget', [
                                    'label' => __('book.budget'),
                                    'type' => 'number',
                                    'currency' => config('money.currency_code'),
                                    'step' => number_step()
                                ]) !!}
                            </div>
                        </div>
                        {!! FormField::radios('has_pdf_page_number', [
                            '1' => __('app.yes'),
                            '0' => __('app.no'),
                        ], [
                            'value' => Setting::for($book)->get('has_pdf_page_number') == '0' ? '0': '1',
                            'label' => __('report.has_pdf_page_number'),
                            'placeholder' => false,
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-primary">{{ __('settings.settings') }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                {!! FormField::radios('status_id', [
                                    App\Models\Book::STATUS_INACTIVE => __('book.status_inactive'),
                                    App\Models\Book::STATUS_ACTIVE => __('app.active')
                                ], ['label' => __('app.status')]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! FormField::radios('report_visibility_code', [
                                    App\Models\Book::REPORT_VISIBILITY_PUBLIC => __('category.report_visibility_public'),
                                    App\Models\Book::REPORT_VISIBILITY_INTERNAL => __('category.report_visibility_internal')
                                ], ['label' => __('category.report_visibility')]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {!! FormField::select('report_periode_code', [
                                    App\Models\Book::REPORT_PERIODE_IN_MONTHS => __('report.in_months'),
                                    App\Models\Book::REPORT_PERIODE_IN_WEEKS => __('report.in_weeks'),
                                    App\Models\Book::REPORT_PERIODE_ALL_TIME => __('report.all_time'),
                                ], ['label' => __('report.periode'), 'placeholder' => false]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! FormField::select('start_week_day_code', [
                                    'monday' => __('time.days.monday'),
                                    'tuesday' => __('time.days.tuesday'),
                                    'wednesday' => __('time.days.wednesday'),
                                    'thursday' => __('time.days.thursday'),
                                    'friday' => __('time.days.friday'),
                                    'saturday' => __('time.days.saturday'),
                                    'sunday' => __('time.days.sunday'),
                                ], ['label' => __('report.start_week_day'), 'placeholder' => false]) !!}
                            </div>
                        </div>
                        {!! FormField::text('management_title', [
                            'value' => Setting::for($book)->get('management_title'),
                            'label' => __('book.management_title'),
                            'placeholder' => __('report.management'),
                            'info' => ['text' => __('book.management_title_info_text')],
                        ]) !!}
                        @can('change-manager', $book)
                            {!! FormField::select('manager_id', $financeUsers, [
                                'label' => __('book.manager'),
                                'placeholder' => __('book.admin_only'),
                                'info' => ['text' => __('book.manager_info_text')],
                            ]) !!}
                        @else
                            {!! FormField::textDisplay(__('book.manager'), $book->manager->name) !!}
                        @endcan
                    </div>
                </div>
                <hr class="my-3">
                <legend>{{ __('report.signatures') }}</legend>
                <div class="row">
                    <div class="col-md-4">
                        <h4 class="text-primary">{{ __('app.left_part') }}</h4>
                        {!! FormField::text('acknowledgment_text_left', [
                            'value' => Setting::for($book)->get('acknowledgment_text_left'),
                            'label' => __('report.acknowledgment_text'),
                        ]) !!}
                        {!! FormField::text('sign_position_left', [
                            'value' => Setting::for($book)->get('sign_position_left'),
                            'label' => __('report.sign_position'),
                        ]) !!}
                        {!! FormField::text('sign_name_left', [
                            'value' => Setting::for($book)->get('sign_name_left'),
                            'label' => __('report.sign_name'),
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-primary">{{ __('app.mid_part') }}</h4>
                        {!! FormField::text('acknowledgment_text_mid', [
                            'value' => Setting::for($book)->get('acknowledgment_text_mid'),
                            'label' => __('report.acknowledgment_text'),
                        ]) !!}
                        {!! FormField::text('sign_position_mid', [
                            'value' => Setting::for($book)->get('sign_position_mid'),
                            'label' => __('report.sign_position'),
                        ]) !!}
                        {!! FormField::text('sign_name_mid', [
                            'value' => Setting::for($book)->get('sign_name_mid'),
                            'label' => __('report.sign_name'),
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-primary">{{ __('app.right_part') }}</h4>
                        {!! FormField::text('acknowledgment_text_right', [
                            'value' => Setting::for($book)->get('acknowledgment_text_right'),
                            'label' => __('report.acknowledgment_text'),
                        ]) !!}
                        {!! FormField::text('sign_position_right', [
                            'value' => Setting::for($book)->get('sign_position_right'),
                            'label' => __('report.sign_position'),
                        ]) !!}
                        {!! FormField::text('sign_name_right', [
                            'value' => Setting::for($book)->get('sign_name_right'),
                            'label' => __('report.sign_name'),
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{ Form::submit(__('book.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('books.show', __('app.cancel'), [$book], ['class' => 'btn btn-link']) }}
                @can('delete', $book)
                    {{ link_to_route('books.edit', __('app.delete'), [$book, 'action' => 'delete'], ['class' => 'btn btn-danger float-right', 'id' => 'del-book-'.$book->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        inline: true,
    });
})();
</script>
@endpush

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        inline: true,
        scrollMonth: false,
    });
})();
</script>
@endpush
