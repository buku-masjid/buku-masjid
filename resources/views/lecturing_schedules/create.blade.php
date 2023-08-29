@extends('layouts.app')

@section('title', __('lecturing_schedule.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.create') }}</div>
            {{ Form::open(['route' => 'lecturing_schedules.store']) }}
            <div class="card-body">
                {!! FormField::radios('audience_code', $audienceCodes, [
                    'required' => true,
                    'label' => __('lecturing_schedule.audience'),
                    'value' => old('audience_code', App\Models\LecturingSchedule::AUDIENCE_PUBLIC),
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('date', [
                            'required' => true,
                            'label' => __('lecturing_schedule.date'),
                            'value' => old('date', date('Y-m-d')),
                            'class' => 'date-select',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">{!! FormField::text('start_time', ['required' => true, 'label' => __('lecturing_schedule.start_time'), 'placeholder' => '19:00']) !!}</div>
                            <div class="col-6">{!! FormField::text('end_time', ['label' => __('lecturing_schedule.end_time'), 'placeholder' => '19:40']) !!}</div>
                            <div class="col-12">{!! FormField::text('time_text', ['label' => __('lecturing_schedule.time_text'), 'placeholder' => __('lecturing_schedule.time_text_placeholder')]) !!}</div>
                        </div>
                    </div>
                </div>
                {!! FormField::text('lecturer_name', ['required' => true, 'label' => __('lecturing_schedule.lecturer_name')]) !!}
                {!! FormField::text('book_title', ['label' => __('lecturing_schedule.book_title')]) !!}
                {!! FormField::text('book_writer', ['label' => __('lecturing_schedule.book_writer')]) !!}
                {!! FormField::text('book_link', ['label' => __('lecturing_schedule.book_link')]) !!}
                {!! FormField::text('video_link', ['label' => __('lecturing_schedule.video_link')]) !!}
                {!! FormField::text('audio_link', ['label' => __('lecturing_schedule.audio_link')]) !!}
                {!! FormField::text('title', ['label' => __('lecturing_schedule.title')]) !!}
                {!! FormField::textarea('description', ['label' => __('lecturing_schedule.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('app.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('lecturing_schedules.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
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
