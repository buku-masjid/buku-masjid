@extends('layouts.settings')

@section('title', __('settings.system_info'))

@section('content_settings')

<div class="page-header">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">{{ __('settings.system_info') }}</h2>
        </div>
    </div>
</div>

<div class="page-body">
<div class="row">
    <div class="col-md-3">
        @livewire('system-info.disk-usage')
    </div>
</div>
</div>
@endsection
