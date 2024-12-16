@extends('layouts.settings')

@section('title', __('lecturing.create'))

@section('content_settings')
<div class="row justify-content-center mt-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('lecturing.create') }}</div>
            @if ($originalLecturing)
                {{ Form::model($originalLecturing, ['route' => 'lecturings.store']) }}
            @else
                {{ Form::open(['route' => 'lecturings.store']) }}
            @endif
            <div class="card-body">
                {!! FormField::radios('audience_code', $audienceCodes, [
                    'required' => true,
                    'label' => __('lecturing.audience'),
                    'value' => old('audience_code', App\Models\Lecturing::AUDIENCE_PUBLIC),
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('date', [
                            'required' => true,
                            'label' => __('lecturing.date'),
                            'value' => old('date', today()->format('Y-m-d')),
                            'class' => 'date-select',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">{!! FormField::text('start_time', ['required' => true, 'label' => __('lecturing.start_time'), 'placeholder' => '19:00']) !!}</div>
                            <div class="col-6">{!! FormField::text('end_time', ['label' => __('lecturing.end_time'), 'placeholder' => '19:40']) !!}</div>
                            <div class="col-12">{!! FormField::text('time_text', ['label' => __('lecturing.time_text'), 'placeholder' => __('lecturing.time_text_placeholder')]) !!}</div>
                            <div class="col-12">{!! FormField::text('lecturer_name', ['required' => true, 'label' => __('lecturing.lecturer_name')]) !!}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! FormField::text('imam_name', ['label' => __('lecturing.imam_name')]) !!}</div>
                    <div class="col-md-6">{!! FormField::text('muadzin_name', ['label' => __('lecturing.muadzin_name')]) !!}</div>
                </div>
                {!! FormField::text('book_title', ['label' => __('lecturing.book_title')]) !!}
                {!! FormField::text('book_writer', ['label' => __('lecturing.book_writer')]) !!}
                {!! FormField::text('book_link', ['label' => __('lecturing.book_link')]) !!}
                {!! FormField::text('video_link', ['label' => __('lecturing.video_link')]) !!}
                {!! FormField::text('audio_link', ['label' => __('lecturing.audio_link')]) !!}
                {!! FormField::text('title', ['label' => __('lecturing.title')]) !!}
                {!! FormField::textarea('description', ['label' => __('lecturing.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('lecturings.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
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
        inline: true,
        scrollMonth: false,
    });
})();
</script>
@endpush
