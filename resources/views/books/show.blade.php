@extends('layouts.settings')

@section('title', __('book.detail').' - '.$book->name)

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $book->name }}</h1>
    <div class="page-subtitle">{{ __('book.detail') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('books.index', __('book.back_to_index'), [], ['class' => 'btn btn-secondary']) }}
    </div>
</div>

<div class="row">
    <div class="col-md-2">@include('books._show_nav_tabs')</div>
    <div class="col-md-10">
        @includeWhen(request('tab') == null, 'books._show_book_settings')
        @includeWhen(request('tab') == 'signatures', 'books._show_book_signatures')
        @includeWhen(request('tab') == 'landing_page', 'books._show_book_landing_page')
    </div>
</div>

@endsection
