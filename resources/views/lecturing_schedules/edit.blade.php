@extends('layouts.app')

@section('title', __('lecturing_schedule.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $lecturingSchedule)
        @can('delete', $lecturingSchedule)
            <div class="card">
                <div class="card-header">{{ __('lecturing_schedule.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('lecturing_schedule.title') }}</label>
                    <p>{{ $lecturingSchedule->title }}</p>
                    <label class="control-label text-primary">{{ __('lecturing_schedule.description') }}</label>
                    <p>{{ $lecturingSchedule->description }}</p>
                    {!! $errors->first('lecturing_schedule_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('lecturing_schedule.delete_confirm') }}</div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['lecturing_schedules.destroy', $lecturingSchedule]],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['lecturing_schedule_id' => $lecturingSchedule->id]
                    ) !!}
                    {{ link_to_route('lecturing_schedules.edit', __('app.cancel'), [$lecturingSchedule], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">
                <span class="card-options">{{ $lecturingSchedule->audience }}</span>
                {{ __('lecturing_schedule.edit') }}
            </div>
            {{ Form::model($lecturingSchedule, ['route' => ['lecturing_schedules.update', $lecturingSchedule], 'method' => 'patch']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('date', [
                            'required' => true,
                            'label' => __('lecturing_schedule.date'),
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
                {{ Form::submit(__('lecturing_schedule.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('lecturing_schedules.show', __('app.cancel'), [$lecturingSchedule], ['class' => 'btn btn-link']) }}
                @can('delete', $lecturingSchedule)
                    {{ link_to_route('lecturing_schedules.edit', __('app.delete'), [$lecturingSchedule, 'action' => 'delete'], ['class' => 'btn btn-danger float-right', 'id' => 'del-lecturing_schedule-'.$lecturingSchedule->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
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
