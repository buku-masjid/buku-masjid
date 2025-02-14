@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<section class="bg-white">
    <div class="container-md">
        <div class="section-hero row">
          <div class="col">
              @include('layouts._public_masjid_info')
              
              <!-- <div class="mt-3 mt-lg-5">
                <span class="text-secondary">Nama Kontak</span>
                <h2>KH. Ahmad Mubarok</h2>
              </div> -->

              <div class="mt-3 pt-3 border-top">
                <span class="text-secondary">Nomor Ponsel</span>
                <a href="https://wa.me/628123456789"><h2> <i class="ti">&#xec74;</i>{{ Setting::get('masjid_whatsapp_number') }} </h2></a>
              </div>
              <div class="mt-3 pt-3 border-top">
                <!-- Facebook -->
                <div class="mb-2"><i class="ti">&#xf7e6;</i> <span class="fs-4"> {{ Setting::get('masjid_facebook_username') }}</span></div>
                <!-- Instagram -->
                <div class="mb-2"><i class="ti">&#xec20;</i> <span class="fs-4"> {{ Setting::get('masjid_instagram_username') }}</span></div>
                <!-- Youtube -->
                <div><i class="ti">&#xfc22;</i> <span class="fs-4"> {{ Setting::get('masjid_youtube_username') }}</span></div>
              </div>
          </div>
          <div class="col-sm-8 position-relative mt-4 mt-lg-0">
            <div class="d-none d-lg-inline position-absolute card p-2 shadow" style="width: 300px; z-index: 5; bottom: -40px; left: -30px">
              <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}">
            </div>

            <!-- MAP -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
            <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="crossorigin=""></script>
            <div class="card p-3 shadow-lg" style="z-index: 0">
              <div class="w-100" id="masjid_map" style="min-height: 600px; z-index: 0">
              
              </div>
            </div>
            
            <script>
                var latitude = "{{ Setting::get('masjid_latitude') }}";
                var longitude = "{{ Setting::get('masjid_longitude') }}";
                console.log(latitude)
                var map = L.map('masjid_map', {
                    scrollWheelZoom: false,
                }).setView([latitude, longitude], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([latitude, longitude]).addTo(map)
                    .bindPopup("{{ Setting::get('masjid_name', config('masjid.name')) }}").openPopup();
            </script>
            
          </div>
        </div>
    </div>
</section>
@endsection
