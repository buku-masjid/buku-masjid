@extends('layouts.app')

@section('title', __('lecturing_schedule.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\LecturingSchedule)
            {{ link_to_route('lecturing_schedules.create', __('lecturing_schedule.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h1 class="page-title">{{ __('lecturing_schedule.list') }} <small>{{ __('app.total') }} : {{ $lecturingSchedules->count() }} {{ __('lecturing_schedule.lecturing_schedule') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                    {{ Form::text('q', request('q'), ['class' => 'form-control form-control-sm mr-2', 'placeholder' => __('lecturing_schedule.search_text')]) }}
                    {{ Form::select('month', get_months(), $month, ['class' => 'form-control form-control-sm mr-2']) }}
                    {{ Form::select('year', get_years(), $year, ['class' => 'form-control form-control-sm mr-2']) }}
                    <div class="form-group mt-4 mt-sm-0">
                        {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm mr-2']) }}
                        {{ link_to_route('lecturing_schedules.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm mr-2']) }}
                        @livewire('prev-month-button', ['routeName' => 'lecturing_schedules.index', 'buttonClass' => 'btn btn-secondary btn-sm mr-2'])
                        @livewire('next-month-button', ['routeName' => 'lecturing_schedules.index', 'buttonClass' => 'btn btn-secondary btn-sm'])
                    </div>
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('lecturing_schedule.audience') }}</th>
                        <th class="text-center">{{ __('time.day_name') }}</th>
                        <th class="text-center">{{ __('time.date') }}</th>
                        <th>{{ __('lecturing_schedule.time') }}</th>
                        <th>{{ __('lecturing_schedule.lecturer') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lecturingSchedules as $key => $lecturingSchedule)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $lecturingSchedule->audience }}</td>
                        <td class="text-center">{{ $lecturingSchedule->day_name }}</td>
                        <td class="text-center">{{ $lecturingSchedule->date }}</td>
                        <td>{{ $lecturingSchedule->time }}</td>
                        <td>{{ $lecturingSchedule->lecturer }}</td>
                        <td class="text-center">
                            @can('view', $lecturingSchedule)
                                {{ link_to_route(
                                    'lecturing_schedules.show',
                                    __('app.show'),
                                    [$lecturingSchedule],
                                    ['id' => 'show-lecturing_schedule-' . $lecturingSchedule->id]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
