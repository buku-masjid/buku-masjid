@extends('layouts.settings')

@section('title', __('masjid_profile.edit'))

@section('content_settings')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="page-header"><h1 class="page-title">@yield('title')</h1></div>
        <div class="card">
            {{ Form::open(['route' => 'masjid_profile.update', 'method' => 'patch']) }}
                <div class="card-body">
                    {!! FormField::text('masjid_name', ['required' => true, 'value' => old('masjid_name', Setting::get('masjid_name')), 'label' => __('masjid_profile.name')]) !!}
                    {!! FormField::textarea('masjid_address', ['required' => true, 'value' => old('masjid_address', Setting::get('masjid_address')), 'label' => __('masjid_profile.address')]) !!}
                    {!! FormField::text('masjid_google_maps_link', ['value' => old('masjid_google_maps_link', Setting::get('masjid_google_maps_link')), 'label' => __('masjid_profile.google_maps_link')]) !!}
                </div>
                <div class="card-footer">
                    {{ Form::submit(__('masjid_profile.update'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('masjid_profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
