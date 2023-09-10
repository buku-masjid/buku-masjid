@extends('layouts.app')

@section('title', __('lecturing_schedule.create_for_friday'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.create_for_friday') }}</div>
            {{ Form::open(['route' => 'friday_lecturing_schedules.store']) }}
            <div class="card-body">
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
                            <div class="col-6">{!! FormField::text('start_time', ['required' => true, 'label' => __('lecturing_schedule.start_time'), 'placeholder' => '12:15']) !!}</div>
                        </div>
                        {!! FormField::text('lecturer_name', ['required' => true, 'label' => __('lecturing_schedule.friday_lecturer_name')]) !!}
                        {!! FormField::text('title', ['label' => __('lecturing_schedule.title')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! FormField::text('video_link', ['label' => __('lecturing_schedule.video_link')]) !!}</div>
                    <div class="col-md-6">{!! FormField::text('audio_link', ['label' => __('lecturing_schedule.audio_link')]) !!}</div>
                </div>
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
