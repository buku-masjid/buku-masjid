@extends('layouts.app')

@section('title', __('lecturing_schedule.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('lecturing_schedule.title') }}</td><td>{{ $lecturingSchedule->title }}</td></tr>
                        <tr><td>{{ __('lecturing_schedule.description') }}</td><td>{{ $lecturingSchedule->description }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $lecturingSchedule)
                    {{ link_to_route('lecturing_schedules.edit', __('lecturing_schedule.edit'), [$lecturingSchedule], ['class' => 'btn btn-warning', 'id' => 'edit-lecturing_schedule-'.$lecturingSchedule->id]) }}
                @endcan
                {{ link_to_route('lecturing_schedules.index', __('lecturing_schedule.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
