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
                    <label class="form-label text-primary">{{ __('lecturing_schedule.title') }}</label>
                    <p>{{ $lecturingSchedule->title }}</p>
                    <label class="form-label text-primary">{{ __('lecturing_schedule.description') }}</label>
                    <p>{{ $lecturingSchedule->description }}</p>
                    {!! $errors->first('lecturing_schedule_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('lecturing_schedule.delete_confirm') }}</div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('lecturing_schedules.destroy', $lecturingSchedule) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;{{ __('app.delete_confirm') }}&quot;)" class="del-form float-right" style="display: inline;">
                        {{ csrf_field() }} {{ method_field('delete') }}
                        <input name="lecturing_schedule_id" type="hidden" value="{{ $lecturingSchedule->id }}">
                        <button type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
                    </form>
                    <a href="{{ route('lecturing_schedules.edit', $lecturingSchedule) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('lecturing_schedule.edit') }}</div>
            <form method="POST" action="{{ route('lecturing_schedules.update', $lecturingSchedule) }}" accept-charset="UTF-8">
                {{ csrf_field() }} {{ method_field('patch') }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">{{ __('lecturing_schedule.title') }} <span class="form-required">*</span></label>
                        <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $lecturingSchedule->title) }}" required>
                        {!! $errors->first('title', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('lecturing_schedule.description') }}</label>
                        <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" rows="4">{{ old('description', $lecturingSchedule->description) }}</textarea>
                        {!! $errors->first('description', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('lecturing_schedule.update') }}" class="btn btn-success">
                    <a href="{{ route('lecturing_schedules.show', $lecturingSchedule) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                    @can('delete', $lecturingSchedule)
                        <a href="{{ route('lecturing_schedules.edit', [$lecturingSchedule, 'action' => 'delete']) }}" id="del-lecturing_schedule-{{ $lecturingSchedule->id }}" class="btn btn-danger float-right">{{ __('app.delete') }}</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
