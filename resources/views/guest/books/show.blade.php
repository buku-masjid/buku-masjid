@extends('layouts.guest')

@section('title', $book->name)

@section('content')
<style>
    .pattern { display: none !important;}
</style>
<section class="bg-white">
    <div class="container-md">
        <div class="section-hero row">
            <div class="col">
                @include('layouts._public_masjid_info')
            </div>
            <div class="d-none d-lg-flex align-items-center col-7 position-relative">
                <img src="{{ asset('images/donate_infaq.png') }}" style="width: 80%">
                <img src="{{ asset('images/donate_pattern.svg') }}" class="position-absolute" style="z-index: 0">
            </div>
        </div>
    </div>
</section>
<div class="section-bottom">
    <div class="container-md p-3 home-bottom">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h2 class="fw-bolder mb-3">{{ $book->name }}</h2>
                <div class="fs-2 mb-3">{{ $book->description }}</div>

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">
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
                    <div class="card-body">
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
    </div>
</div>
@endsection
