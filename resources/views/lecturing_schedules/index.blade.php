@extends('layouts.app')

@section('title', __('lecturing_schedule.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\LecturingSchedule)
            <a href="{{ route('lecturing_schedules.create') }}" class="btn btn-success">{{ __('lecturing_schedule.create') }}</a>
        @endcan
    </div>
    <h1 class="page-title">{{ __('lecturing_schedule.list') }} <small>{{ __('app.total') }} : {{ $lecturingSchedules->total() }} {{ __('lecturing_schedule.lecturing_schedule') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="form-label">{{ __('lecturing_schedule.search') }}</label>
                        <input placeholder="{{ __('lecturing_schedule.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('lecturing_schedule.search') }}" class="btn btn-secondary">
                    <a href="{{ route('lecturing_schedules.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
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
                        <td>{!! $lecturingSchedule->title_link !!}</td>
                        <td>{{ $lecturingSchedule->description }}</td>
                        <td class="text-center">
                            @can('view', $lecturingSchedule)
                                <a href="{{ route('lecturing_schedules.show', $lecturingSchedule) }}" id="show-lecturing_schedule-{{ $lecturingSchedule->id }}">{{ __('app.show') }}</a>
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
