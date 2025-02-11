@extends('layouts.settings')

@section('title', __('masjid_profile.masjid_profile'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">@yield('title')</h1>
    <div class="page-options">
        @can('edit_masjid_profile')
            {{ link_to_route('masjid_profile.edit', __('masjid_profile.edit'), [], ['class' => 'btn btn-warning text-dark']) }}
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <table class="table table-sm card-table">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">
                            @if (Setting::get('masjid_logo_path'))
                                <img class="img-fluid my-4" src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" alt="{{ Setting::get('masjid_name', config('masjid.name')) }}">
                            @else
                                <div class="p-4">{{ __('masjid_profile.masjid_logo') }}</div>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="col-4">{{ __('masjid_profile.name') }}</td><td>{{ Setting::get('masjid_name', config('masjid.name')) }}</td></tr>
                    <tr><td>{{ __('masjid_profile.address') }}</td><td>{{ Setting::get('masjid_address') }}</td></tr>
                    <tr><td>{{ __('masjid_profile.city_name') }}</td><td>{{ Setting::get('masjid_city_name') }}</td></tr>
                    <tr>
                        <td>{{ __('masjid_profile.google_maps_link') }}</td>
                        <td>
                            {{ Setting::get('masjid_google_maps_link') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <div class="card-header">{{ __('app.social_media') }}</div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr>
                        <td class="col-4">Whatsapp</td>
                        <td>
                            @if (Setting::get('masjid_whatsapp_number'))
                                {{ link_to('https://wa.me/'.Setting::get('masjid_whatsapp_number'), null, ['target' => '__blank']) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Instagram</td>
                        <td>
                            @if (Setting::get('masjid_instagram_username'))
                                {{ link_to('https://instagram.com/'.Setting::get('masjid_instagram_username'), null, ['target' => '__blank']) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Youtube</td>
                        <td>
                            @if (Setting::get('masjid_youtube_username'))
                                {{ link_to('https://youtube.com/'.Setting::get('masjid_youtube_username'), null, ['target' => '__blank']) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Facebook</td>
                        <td>
                            @if (Setting::get('masjid_facebook_username'))
                                {{ link_to('https://facebook.com/'.Setting::get('masjid_facebook_username'), null, ['target' => '__blank']) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Telegram</td>
                        <td>
                            @if (Setting::get('masjid_telegram_username'))
                                {{ link_to('https://t.me/'.Setting::get('masjid_telegram_username'), null, ['target' => '__blank']) }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        @if (Setting::get('masjid_google_maps_link'))
            <div class="card">
                <div class="card-header">
                    {{ __('masjid_profile.maps') }}
                    <div class="card-options">
                        {!! FormField::formButton(
                            ['route' => 'masjid_profile.coordinates.update', 'method' => 'patch'],
                            '<i class="fe fe-map"></i> '.__('masjid_profile.refresh_masjid_map'),
                            ['id' => 'refresh_masjid_map', 'class' => 'btn btn-info btn-sm'],
                            ['google_maps_link' => Setting::get('masjid_google_maps_link')]
                        ) !!}
                    </div>
                </div>
                @if (Setting::get('masjid_latitude') && Setting::get('masjid_longitude'))
                    <div class="card-body" id="masjid_map"></div>
                @endif
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                {{ __('masjid_profile.masjid_photo') }}
                <div class="card-options"></div>
            </div>
            <div class="card-body">
                @if (Setting::get('masjid_photo_path'))
                    <img class="img-fluid" src="{{ Storage::url(Setting::get('masjid_photo_path'))}}" alt="{{ Setting::get('masjid_name', config('masjid.name')) }}">
                @else
                    <div class="p-4">{{ __('masjid_profile.masjid_photo') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@if (Setting::get('masjid_latitude') && Setting::get('masjid_longitude'))
    @section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
        integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
        crossorigin=""/>
    <style>
        #masjid_map { min-height: 500px; }
    </style>
    @endsection

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
        integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
        crossorigin=""></script>
    <script>
        var latitude = "{{ Setting::get('masjid_latitude') }}";
        var longitude = "{{ Setting::get('masjid_longitude') }}";

        var map = L.map('masjid_map', {
            scrollWheelZoom: false,
        }).setView([latitude, longitude], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map)
            .bindPopup("{{ Setting::get('masjid_name', config('masjid.name')) }}").openPopup();
    </script>
    @endpush
@endif
