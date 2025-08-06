@extends('layouts.public_display')

@section('bukumasjid_logo_image')
<img src="{{ asset('images/bm_logo_teal.svg') }}" alt="Prayer Image" class="position-absolute top-0 end-0 me-5 mt-4">
@endsection

@section('content')
    <div id="prayerModal" class="prayer-modal">
        <div class="prayer-message">Sholat Sedang Berlangsung</div>
    </div>
    <div class="jm-section jm-header">
        <div class="jm-left-column pe-3 align-items-center justify-content-start jm-card me-4 text-start">
            @include('public_display.themes.default._masjid_info')
        </div>
        <div class="jm-right-column jm-card justify-content-center align-items-center overflow-hidden">
            @livewire('public-display.financial-summary')
        </div>
    </div>
    <div class="jm-section jm-main-content">
        <div class="jm-left-column col-6 split py-4 pe-4">
            @include('public_display.themes.light._date_time')
            @include('public_display.themes.light._sharing_info')
        </div>
        <div class="jm-right-column col-6 py-4">
            @include('public_display.themes.light._carousel')
        </div>
    </div>
    <div class="jm-section jm-footer">
    @include('public_display.themes.default._shalat_time')
    </div>
@endsection
