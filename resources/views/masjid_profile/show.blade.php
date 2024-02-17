@extends('layouts.settings')

@section('title', __('masjid_profile.masjid_profile'))

@section('content_settings')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="page-header">
            <h1 class="page-title"><h1 class="page-title">@yield('title')</h1></h1>
        </div>
        <div class="card">
            <table class="table table-sm card-table">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">
                        @if (Setting::get('masjid_logo_path'))
                            <img class="img-fluid my-4" src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" alt="{{ Setting::get('masjid_name') ?? 'buku masjid'}}">
                        @endif
                        </td>
                    </tr>
                    <tr><td class="col-4">{{ __('masjid_profile.name') }}</td><td>{{ Setting::get('masjid_name', config('masjid.name')) }}</td></tr>
                    <tr><td>{{ __('masjid_profile.address') }}</td><td>{{ Setting::get('masjid_address') }}</td></tr>
                    <tr><td>{{ __('masjid_profile.google_maps_link') }}</td><td>{{ Setting::get('masjid_google_maps_link') }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('edit_masjid_profile')
                    {{ link_to_route('masjid_profile.edit', __('masjid_profile.edit'), [], ['class' => 'btn btn-success']) }}
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
