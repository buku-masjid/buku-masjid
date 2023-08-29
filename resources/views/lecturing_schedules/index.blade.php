@extends('layouts.app')

@section('title', __('lecturing_schedule.list'))

@section('content')
<div class="card">
    <div class="card-body">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
            {{ Form::text('q', request('q'), ['class' => 'form-control mr-0 mr-sm-2', 'placeholder' => __('lecturing_schedule.search_text')]) }}
            {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-0 mr-sm-2']) }}
            {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-0 mr-sm-2']) }}
            <div class="form-group mt-4 mt-sm-0">
                {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary mr-0 mr-sm-2']) }}
                {{ link_to_route('lecturing_schedules.index', __('app.reset'), [], ['class' => 'btn btn-secondary mr-0 mr-sm-2']) }}
                @livewire('prev-month-button', ['routeName' => 'lecturing_schedules.index', 'buttonClass' => 'btn btn-secondary mr-0 mr-sm-2'])
                @livewire('next-month-button', ['routeName' => 'lecturing_schedules.index', 'buttonClass' => 'btn btn-secondary mr-0 mr-sm-2'])
            </div>
            <div class="form-group mt-0 mt-sm-0">
                @can('create', new App\Models\LecturingSchedule)
                    {{ link_to_route('lecturing_schedules.create', __('lecturing_schedule.create'), [], ['class' => 'btn btn-success mr-0 mr-sm-2']) }}
                    {{ link_to_route('friday_lecturing_schedules.create', __('lecturing_schedule.create_for_friday'), [], ['class' => 'btn btn-primary']) }}
                @endcan
            </div>
        {{ Form::close() }}
    </div>
</div>

@foreach ($audienceCodes as $audienceCode => $audience)
    <div class="page-header mb-4">
        <h2 class="page-title">{{ __('lecturing_schedule.audience_'.$audienceCode) }}</h2>
    </div>

    @desktop
        @include('lecturing_schedules._'.$audienceCode)
    @elsedesktop
        @if (isset($lecturingSchedules[$audienceCode]))
            @foreach($lecturingSchedules[$audienceCode] as $lecturingSchedule)
                @include('lecturing_schedules._single_'.$audienceCode)
            @endforeach
        @else
            <p>{{ __('lecturing_schedule.'.$audienceCode.'_empty') }}</p>
        @endif
    @enddesktop
@endforeach

@endsection
