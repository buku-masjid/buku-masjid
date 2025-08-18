@extends('layouts.public_display')

@section('bukumasjid_logo_image')
@includeFirst(["public_display.themes.$theme._buku_masjid_logo", 'public_display.themes.default._buku_masjid_logo'])
@endsection

@section('content')
    <div class="lg:flex md:h-[15vh] 2xl:h-[10vh] items-center">
        <div class="md:w-1/3 lg:w-1/4 lg:h-full mb-2 lg:mb-0 items-center justify-content-start jm-card text-start">
            @includeFirst(["public_display.themes.$theme._masjid_info", 'public_display.themes.default._masjid_info'])
        </div>
        <div class="lg:ms-3 md:w-2/3 lg:w-3/4 lg:h-full jm-card flex justify-content-start items-center overflow-hidden">
            @livewire('public-display.financial-summary', ['theme' => $theme])
        </div>
    </div>
    <div class="lg:flex w-full md:h-[65vh] 2xl:h-[70vh]">
        <div class="col-6 md:w-1/3 lg:w-1/4 split py-4 md:py-2 2xl:py-4 lg:h-full">
            @includeFirst(["public_display.themes.$theme._date_time", 'public_display.themes.default._date_time'])
            @includeFirst(["public_display.themes.$theme._sharing_info", 'public_display.themes.default._sharing_info'])
        </div>
        <div class="lg:ms-3 col-6 md:w-2/3 lg:w-3/4 py-4 md:py-2 2xl:py-4 lg:h-full">
            @includeFirst(["public_display.themes.$theme._carousel", 'public_display.themes.default._carousel'])
        </div>
    </div>
    <div class="lg:flex pt-0 md:h-[20vh] 2xl:h-[20vh] w-full">
        @includeFirst(["public_display.themes.$theme._shalat_time", 'public_display.themes.default._shalat_time'])
    </div>
@endsection
