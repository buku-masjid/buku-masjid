@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')

<section class="bg-white">
    <div class="container-md">
        @if (config('features.shalat_time.is_active'))
            @include('guest._welcome_shalat_time_matrix')
        @endif
        <div class="section-hero row" style="padding-top: 3em">
            <div class="col">
                @include('layouts.public._masjid_info')
            </div>
            <div class="d-none d-lg-block col-6 position-relative">
                @if (Setting::get('masjid_photo_path'))
                    <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}">
                @else
                    <div style="background-color: #f8f8f8; height: 360px"></div>
                @endif
                <img src="{{ asset('images/image_cover.svg') }}" class="position-absolute top-0 start-0">
            </div>
        </div>
    </div>
</section>
<div class="section-bottom">
    <div class="container-md home-bottom">
        <div>
            <div class="row align-items-end">
                @livewire('public-home.weekly-financial-summary')
            </div>
            </div>
            <div class="col-lg-12">
                <div class="row align-items-start">
                    <div class="col-lg-6 mt-3">
                        @livewire('public-home.book-cards')
                    </div>
                    <div class="col-lg-6 mt-3">
                        @if (Route::has('lecturings.index'))
                            @livewire('public-home.daily-lecturings', ['date' => today(), 'dayTitle' => 'today'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
