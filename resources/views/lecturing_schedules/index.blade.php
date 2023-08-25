@extends('layouts.app')

@section('title', __('lecturing_schedule.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\LecturingSchedule)
            {{ link_to_route('lecturing_schedules.create', __('lecturing_schedule.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h1 class="page-title">{{ __('lecturing_schedule.list') }} <small>{{ __('app.total') }} : {{ $lecturingSchedules->total() }} {{ __('lecturing_schedule.lecturing_schedule') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('lecturing_schedule.search'), 'placeholder' => __('lecturing_schedule.search_text'), 'class' => 'mx-sm-2']) !!}
                {{ Form::submit(__('lecturing_schedule.search'), ['class' => 'btn btn-secondary']) }}
                {{ link_to_route('lecturing_schedules.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('lecturing_schedule.title') }}</th>
                        <th>{{ __('lecturing_schedule.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lecturingSchedules as $key => $lecturingSchedule)
                    <tr>
                        <td class="text-center">{{ $lecturingSchedules->firstItem() + $key }}</td>
                        <td>{{ $lecturingSchedule->title_link }}</td>
                        <td>{{ $lecturingSchedule->description }}</td>
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
            <div class="card-body">{{ $lecturingSchedules->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
