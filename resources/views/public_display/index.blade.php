@extends('layouts.public_display')

@section('bukumasjid_logo_image')
@includeFirst(["public_display.themes.$theme._buku_masjid_logo", 'public_display.themes.default._buku_masjid_logo'])
@endsection

@section('content')
    <div class="jm-section jm-header">
        <div class="jm-left-column pe-3 align-items-center justify-content-start jm-card me-4 text-start">
            @includeFirst(["public_display.themes.$theme._masjid_info", 'public_display.themes.default._masjid_info'])
        </div>
        <div class="jm-right-column jm-card justify-content-center align-items-center overflow-hidden">
            @livewire('public-display.financial-summary')
        </div>
    </div>
    <div class="jm-section jm-main-content">
        <div class="jm-left-column col-6 split py-4 pe-4">
            @includeFirst(["public_display.themes.$theme._date_time", 'public_display.themes.default._date_time'])
            @includeFirst(["public_display.themes.$theme._sharing_info", 'public_display.themes.default._sharing_info'])
        </div>
        <div class="jm-right-column col-6 py-4">
            @includeFirst(["public_display.themes.$theme._carousel", 'public_display.themes.default._carousel'])
        </div>
    </div>
    <div class="jm-section jm-footer">
        @includeFirst(["public_display.themes.$theme._shalat_time", 'public_display.themes.default._shalat_time'])
    </div>
@endsection
