@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<section class="bg-white">
    <div class="container-md">
        <div class="section-hero row justify-content-center">
            <div class="col" style="max-width: 420px">
                @include('layouts.public._masjid_info')
                @include('layouts.public._masjid_social_media')
            </div>
            @if (Setting::get('masjid_photo_path') && Setting::get('masjid_latitude') && Setting::get('masjid_longitude'))
                <div class="col-sm-8 position-relative mt-4 mt-lg-0">
                    @if (Setting::get('masjid_latitude') && Setting::get('masjid_longitude'))
                        @if (Setting::get('masjid_photo_path'))
                            <div class="d-none d-lg-inline position-absolute card p-2 shadow" style="width: 300px; z-index: 5; bottom: -40px; left: -30px">
                                <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}">
                            </div>
                        @endif
                        <div class="card p-3 shadow-lg" style="z-index: 0">
                            <div class="w-100" id="masjid_map" style="min-height: 600px; z-index: 0"></div>
                        </div>
                    @else
                        @if (Setting::get('masjid_photo_path'))
                            <div class="card p-2 shadow">
                                <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}">
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="crossorigin=""></script>
<script>
    var latitude = "{{ Setting::get('masjid_latitude') }}";
    var longitude = "{{ Setting::get('masjid_longitude') }}";
    console.log(latitude)
    var map = L.map('masjid_map', {
        scrollWheelZoom: false,
    }).setView([latitude, longitude], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([latitude, longitude]).addTo(map)
    .bindPopup("{{ Setting::get('masjid_name', config('masjid.name')) }}").openPopup();
</script>
@endpush
