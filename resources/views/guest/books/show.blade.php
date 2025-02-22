@extends('layouts.guest')

@section('title', $book->name)

@section('content')
<style>
    .pattern { display: none !important;}
</style>
<section class="bg-white d-none d-md-block">
    <div class="container-md">
        <div class="section-hero row">
            <div class="mb-3"><a href="/infaq"><i class="ti">&#xea60;</i> Program {{ Setting::get('masjid_name', config('masjid.name')) }}</a></div>
        </div>
    </div>
</section>
<div class="section-bottom">
    <img class="w-full d-block d-md-none" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}">
    <div class="container-md gutter-0 home-bottom">
        <!-- <h2 class="fw-bolder mb-3">{{ $book->name }}</h2>
                <div class="fs-2 mb-3">{{ $book->description }}</div> -->
        <div class="card fw-bold p-3 mb-2 shadow-lg position-relative d-none d-md-block">
            <img class="w-full" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}" style="border-radius: 15px">
        </div>
        <div>
        <div class="p-3">
            <!-- <a href="/infaq">
            <i class="ti">&#xea60;</i> Program {{ Setting::get('masjid_name', config('masjid.name')) }}</a -->
            <h2 class="fw-bolder">{{ $book->name }}</h2>
        </div>
        <div class="card">
            <div class="card-header" >
                <ul class="nav nav-tabs card-header-tabs" style="border-radius: 15px 15px 0 0" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('public.books.show', $book) }}" class="nav-link {{ request('tab') == null ? 'active' : '' }}" role="tab">
                            {{ __('app.description') }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('public.books.show', [$book, 'tab' => 'report']) }}" class="nav-link {{ request('tab') == 'report' ? 'active' : '' }}" role="tab" >
                            {{ __('report.report') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="p-3 p-lg-4">
                <div class="tab-content">
                    <div class="tab-pane active show" role="tabpanel">
                        @includeWhen(request('tab') == null, 'guest.books._show_book_landing_page')
                        @includeWhen(request('tab') == 'report', 'guest.books._show_book_report')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
