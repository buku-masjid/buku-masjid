@extends('layouts.app')

@section('title', __('partner.partner'))

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $partner->name }}</h1>
    <div class="page-subtitle">{{ __('partner.partner') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('partners.index', __('partner.back_to_index'), [], ['class' => 'btn btn-secondary']) }}
    </div>
</div>

@desktop
    <div class="card table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <tr>
                <td class="col-2 text-center">{{ __('partner.name') }}</td>
                <td class="col-2 text-center">{{ __('partner.phone') }}</td>
                <td class="col-2 text-center">{{ __('partner.work') }}</td>
                <td class="col-2 text-center">{{ __('app.status') }}</td>
            </tr>
            <tr>
                <td class="text-center lead" style="border-top: none;">{{ $partner->name }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->phone }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->work }}</td>
                <td class="text-center lead" style="border-top: none;">{{ $partner->status }}</td>
            </tr>
        </table>
    </div>
@elsedesktop
    <div class="card table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <tr><td class="col-4">{{ __('partner.name') }}</td><td>{{ $partner->name }}</td></tr>
            <tr><td>{{ __('partner.phone') }}</td><td>{{ $partner->phone }}</td></tr>
            <tr><td>{{ __('partner.work') }}</td><td>{{ $partner->work }}</td></tr>
            <tr><td>{{ __('app.status') }}</td><td>{{ $partner->status }}</td></tr>
        </table>
    </div>
@enddesktop

@if ($partner->address)
    <div class="alert alert-warning"><strong>{{ __('partner.address') }}:</strong><br>{{ $partner->address }}</div>
@endif

@if ($partner->description)
    <div class="alert alert-info"><strong>{{ __('partner.description') }}:</strong><br>{{ $partner->description }}</div>
@endif
@endsection
