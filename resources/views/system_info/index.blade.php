@extends('layouts.settings')

@section('title', __('settings.system_info'))

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ __('settings.system_info') }}</h1>
    <div class="page-subtitle"></div>
    <div class="page-options d-flex"></div>
</div>

<div class="row">
    <div class="col-md-3">
        @livewire('system-info.disk-usage')
    </div>
</div>
@endsection
