@extends('layouts.settings')

@section('title', __('dashboard.dashboard'))

@section('content_settings')

@include('partners._partner_type_selector')

<div class="text-center my-4">
    <div class="btn-group">
        {!! link_to_route(
            'partners.index',
            __('dashboard.dashboard'),
            ['type_code' => $selectedTypeCode] + request()->all(),
            ['class' => 'btn btn-pill '.(in_array(Request::segment(2), ['dashboard', null]) ? 'btn-primary' : 'btn-secondary')]
        ) !!}
        {!! link_to_route(
            'partners.search',
            __('partner.search', ['type' => $selectedTypeName]),
            ['type_code' => $selectedTypeCode] + request()->all(),
            ['class' => 'btn btn-pill '.(Request::segment(2) == 'search' ? 'btn-primary' : 'btn-secondary')]
        ) !!}
    </div>
</div>

<div class="row my-4 mt-sm-0">
    <div class="col-md-4 text-center text-sm-left">
        <h1 class="page-title">
            {{ __('dashboard.dashboard') }}
        </h1>
        <div class="page-subtitle ml-0">
            {{ __('app.total') }} : {{ $partners->total() }} {{ __('partner.partner_type', ['type' => $selectedTypeName]) }}
            {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-4 mt-3 text-center">
    </div>
    <div class="col-md-4 mt-3 text-center text-sm-right">
        @can('create', new App\Models\Partner)
            {{ link_to_route('partners.create', __('partner.create', ['type' => $selectedTypeName]), request()->only('type_code'), ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        @livewire('partners.marital-statuses', ['partnerTypeCode' => $selectedTypeCode, 'partnerType' => $selectedTypeName])
    </div>
    <div class="col-md-6">
        @livewire('partners.activity-statuses', ['partnerTypeCode' => $selectedTypeCode, 'partnerType' => $selectedTypeName])
    </div>
    <div class="col-md-6">
        @livewire('partners.financial-statuses', ['partnerTypeCode' => $selectedTypeCode, 'partnerType' => $selectedTypeName])
    </div>
    <div class="col-md-6">
        @livewire('partners.age-groups', ['partnerTypeCode' => $selectedTypeCode, 'partnerType' => $selectedTypeName])
    </div>
</div>

@endsection
