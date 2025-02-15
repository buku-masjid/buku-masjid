@extends('layouts.settings')

@section('title', __('book.edit'))

@section('content_settings')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="page-header">
            <h1 class="page-title">{{ $book->name }}</h1>
            <div class="page-subtitle">{{ __('book.landing_page') }}</div>
            <div class="page-options d-flex">
                {{ link_to_route('books.landing_page.show', __('app.cancel'), [$book], ['class' => 'btn btn-secondary float-right']) }}
            </div>
        </div>
        <div class="card">
            {{ Form::model($book, ['route' => ['books.landing_page.update', $book], 'method' => 'patch']) }}
            <div class="card-body">
                <h4 class="text-primary">{{ __('book.landing_page') }}</h4>
                {!! FormField::text('due_date', ['label' => __('book.due_date'), 'class' => 'date-select']) !!}
                {!! FormField::textarea('landing_page_content', ['label' => __('book.landing_page_content')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.save'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('books.show', __('app.cancel'), [$book], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
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
        scrollMonth: false,
    });
})();
</script>
@endpush
