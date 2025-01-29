@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')
<section class="bg-white">
    <div class="container-md">
        <div class="section-hero row">
          <div class="col">
              @include('layouts._public_masjid_info')
              <div class="mt-3 mt-lg-5">
                <span class="text-secondary">Nama Kontak</span>
                <h2>KH. Ahmad Mubarok</h2>
              </div>
              <div class="mt-3 pt-3 border-top">
                <span class="text-secondary">Nomor Ponsel</span>
                <a href="https://wa.me/628123456789"><h2><i class="ti">&#xec74;</i> 0812 3456 789 </h2></a>
              </div>
              <div class="mt-3 pt-3 border-top">
                <!-- Facebook -->
                <div class="mb-2"><i class="ti">&#xf7e6;</i> <span class="fs-4"> NurulHidayah</span></div>
                <!-- Instagram -->
                <div class="mb-2"><i class="ti">&#xec20;</i> <span class="fs-4"> NurulHidayah</span></div>
                <!-- Youtube -->
                <div><i class="ti">&#xfc22;</i> <span class="fs-4"> NurulHidayah</span></div>
              </div>
          </div>
          <div class="col-sm-8 position-relative mt-4 mt-lg-0">
            <div class="d-none d-lg-inline position-absolute card p-2 shadow" style="width: 300px; z-index: 5; bottom: -40px; left: -30px">
              <img src="images/photo_masjid.png">
            </div>
            <div class="card p-3 shadow-lg">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126887.51661327023!2d106.8546278390625!3d-6.36363879999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699369c67c05f5%3A0xec1910a57ceb5492!2sMasjid%20Jami&#39;%20Madinah%20Al-Munawwarah!5e0!3m2!1sen!2sid!4v1732890463439!5m2!1sen!2sid" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>
        </div>
    </div>
</section>
@endsection
