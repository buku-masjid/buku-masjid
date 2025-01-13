@extends('layouts.settings')

@section('title', __('lecturing.create_for_friday'))

@section('content_settings')
<div class="row justify-content-center mt-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('lecturing.create_for_friday') }}</div>
            {{ Form::open(['route' => 'friday_lecturings.store']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('date', [
                            'required' => true,
                            'label' => __('lecturing.date'),
                            'value' => old('date', date('Y-m-d')),
                            'class' => 'date-select',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">{!! FormField::text('start_time', ['required' => true, 'label' => __('lecturing.start_time'), 'placeholder' => '12:15']) !!}</div>
                        </div>
                        {!! FormField::text('lecturer_name', ['required' => true, 'label' => __('lecturing.friday_lecturer_name')]) !!}
                        {!! FormField::text('title', ['label' => __('lecturing.title')]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! FormField::text('imam_name', ['required' => true, 'label' => __('lecturing.imam_name')]) !!}</div>
                    <div class="col-md-6">{!! FormField::text('muadzin_name', ['required' => true, 'label' => __('lecturing.muadzin_name')]) !!}</div>
                    <div class="col-md-6">{!! FormField::text('video_link', ['label' => __('lecturing.video_link')]) !!}</div>
                    <div class="col-md-6">{!! FormField::text('audio_link', ['label' => __('lecturing.audio_link')]) !!}</div>
                </div>
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
