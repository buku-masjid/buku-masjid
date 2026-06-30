@extends('layouts.settings')

@section('title', __('lecturing.list'))

@section('content_settings')
<div class="card mt-4">
    <div class="card-body">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2">
            <div class="col-auto">
                {{ Form::text('q', request('q'), ['class' => 'form-control me-0 me-sm-2', 'placeholder' => __('lecturing.search_text')]) }}
            </div>
            <div class="col-auto">
                {{ Form::select('month', get_months(), $month, ['class' => 'form-control me-0 me-sm-2']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('year', get_years(), $year, ['class' => 'form-control me-0 me-sm-2']) }}
            </div>
            <div class="col-auto">
                {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary me-0 me-sm-2']) }}
                {{ link_to_route('lecturings.index', __('app.reset'), [], ['class' => 'btn me-0 me-sm-2']) }}
                @livewire('prev-month-button', ['routeName' => 'lecturings.index', 'buttonClass' => 'btn me-0 me-sm-2'])
                @livewire('next-month-button', ['routeName' => 'lecturings.index', 'buttonClass' => 'btn me-0 me-sm-2'])
            </div>
            <div class="col-auto">
                @can('create', new App\Models\Lecturing)
                    {{ link_to_route('lecturings.create', __('lecturing.create'), [], ['class' => 'btn btn-success me-0 me-sm-2']) }}
                    {{ link_to_route('friday_lecturings.create', __('lecturing.create_for_friday'), [], ['class' => 'btn btn-primary']) }}
                @endcan
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@foreach ($regularScheduleAudiences as $audienceCode => $audience)
    <div class="page-header mb-4">
        <h2 class="page-title">{{ __('lecturing.audience_'.$audienceCode) }}</h2>
    </div>

    @desktop
        @include('lecturings._'.$audienceCode)
    @elsedesktop
        @if (isset($lecturings[$audienceCode]))
            @foreach($lecturings[$audienceCode] as $lecturing)
                @include('lecturings._single_'.$audienceCode)
            @endforeach
        @else
            <p>{{ __('lecturing.'.$audienceCode.'_empty') }}</p>
        @endif
    @enddesktop
@endforeach

@foreach ($occasionalScheduleAudiences as $audienceCode => $audience)
    @if (isset($lecturings[$audienceCode]))
        <div class="page-header mb-4">
            <h2 class="page-title">{{ __('lecturing.audience_'.$audienceCode) }}</h2>
        </div>
        @desktop
            @include('lecturings._'.$audienceCode)
        @elsedesktop
            @foreach($lecturings[$audienceCode] as $lecturing)
                @include('lecturings._single_'.$audienceCode)
            @endforeach
        @enddesktop
    @endif
@endforeach

@endsection
