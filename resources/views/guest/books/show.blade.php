@extends('layouts.guest')

@section('title', $book->name)

@section('head_tags')
<meta name="description" content="{{ $book->description }}" />
<link rel="canonical" href="{{ route('public.books.show', $book) }}" />
<meta property="og:url" content="{{ route('public.books.show', $book) }}" />
<meta property="og:description" content="{{ $book->description }}" />

@if (Setting::for($book)->get('thumbnail_image_path'))
    <meta property="og:image" content="{{ Storage::url(Setting::for($book)->get('thumbnail_image_path')) }}"/>
@elseif (Setting::for($book)->get('poster_image_path'))
    <meta property="og:image" content="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}"/>
@else
    <div class="p-3 fs-1 d-flex align-items-center justify-content-center bg-info-lt" style="min-height: 207px;border-radius: 15px 15px 0px 0px;">{{ $book->name }}</div>
@endif

@endsection

@section('content')
<section class="bg-white d-none d-md-block">
    <div class="container-md">
        <div class="section-hero row">
            <div class="mb-3">
                <a href="{{ route('public.books.index') }}">
                    <i class="ti">&#xea60;</i>
                    {{ __('book.program') }} {{ Setting::get('masjid_name', config('masjid.name')) }}
                </a>
            </div>
        </div>
    </div>
</section>

<div class="section-bottom">
    @if (Setting::for($book)->get('poster_image_path'))
        <img class="w-full d-block d-md-none" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}">
    @else
        <div class="w-full d-block d-md-none fs-1 d-flex align-items-center justify-content-center bg-info-lt" style="min-height: 8em">{{ $book->name }}</div>
    @endif
    <div class="container-md gutter-0 home-bottom">
        <div class="card fw-bold p-3 mb-2 shadow-lg position-relative d-none d-md-block">
             @if (Setting::for($book)->get('poster_image_path'))
                <img class="w-full" src="{{ Storage::url(Setting::for($book)->get('poster_image_path')) }}" alt="{{ $book->name }}" style="border-radius: 15px">
            @else
                <div class="w-full fs-1 d-flex align-items-center justify-content-center bg-info-lt" style="min-height: 8em">{{ $book->name }}</div>
            @endif
        </div>
        <div class="p-3">
            <h2 class="fw-bolder">{{ $book->name }}</h2>
        </div>
        <div class="p-3">
            @include('guest.books._show_book_report')
        </div>
        <div class="pt-3 mt-4 border-top">
            <h2 class="fw-bolder">Deskripsi</h2>
        </div>
        <div class="p-3">
            @include('guest.books._show_book_landing_page')
        </div> 
    </div>
</div>
@endsection
