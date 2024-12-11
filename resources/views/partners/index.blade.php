@extends('layouts.app')

@section('title', __('dashboard.dashboard'))

@section('content')

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        {!! link_to_route('partners.index', __('dashboard.dashboard'), [], ['class' => 'nav-link'.(in_array(Request::segment(2), ['dashboard', null]) ? ' active' : '')]) !!}
    </li>
    <li class="nav-item">
        {!! link_to_route('partners.search', __('partner.search'), [], ['class' => 'nav-link'.(Request::segment(2) == 'search' ? ' active' : '')]) !!}
    </li>
</ul>

<div class="row my-4 mt-sm-0">
    <div class="col-md-4 text-center text-sm-left">
        <h1 class="page-title">
            {{ __('dashboard.dashboard') }}
        </h1>
        <div class="page-subtitle ml-0">
            {{ __('app.total') }} : {{ $partners->total() }} {{ __('partner.partner') }}
            {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-4 text-center">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline justify-content-center']) }}
        {{ Form::select('type_code', $partnerTypes, request('type_code'), [
            'placeholder' => '-- '.__('partner.all').' --',
            'class' => 'form-control mr-1 mt-2',
            'onchange' => 'submit()',
        ]) }}
        {{ Form::close() }}
    </div>
    <div class="col-md-4 mt-3 text-center text-sm-right">
        @can('create', new App\Models\Partner)
            {{ link_to_route('partners.create', __('partner.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        @livewire('partners.marital-statuses', ['partnerTypeCode' => $selectedTypeCode])
    </div>
    <div class="col-md-6">
        @livewire('partners.activity-statuses', ['partnerTypeCode' => $selectedTypeCode])
    </div>
    <div class="col-md-6">
        @livewire('partners.financial-statuses', ['partnerTypeCode' => $selectedTypeCode])
    </div>
    <div class="col-md-6">
        @livewire('partners.religions', ['partnerTypeCode' => $selectedTypeCode])
    </div>
    <div class="col-md-6">
        @livewire('partners.work-types', ['partnerTypeCode' => $selectedTypeCode])
    </div>
    <div class="col-md-6">
        @livewire('partners.age-groups', ['partnerTypeCode' => $selectedTypeCode])
    </div>
</div>

@endsection
