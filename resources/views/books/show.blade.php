@extends('layouts.settings')

@section('title', __('book.detail').' - '.$book->name)

@section('content_settings')

<div class="page-header">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">{{ $book->name }}</h2>
            <div class="text-secondary mt-1">{{ __('book.detail') }}</div>
        </div>
        <div class="col-auto text-end">
            @can('update', $book)
                {{ link_to_route('books.edit', __('app.edit'), [$book], ['class' => 'btn btn-warning me-0 me-sm-2', 'id' => 'edit-book-'.$book->id]) }}
            @endcan

            {{ link_to_route('books.index', __('book.back_to_index'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
</div>

<div class="page-body">
<div class="row g-0">
    <div class="col-12 col-md-3 col-lg-2">@include('books._show_nav_tabs')</div>
    <div class="col-12 col-md-9 col-lg-10">
        @includeWhen(request('tab') == null, 'books._show_book_settings')
        @includeWhen(request('tab') == 'signatures', 'books._show_book_signatures')
        @includeWhen(request('tab') == 'landing_page', 'books._show_book_landing_page')
    </div>
</div>
</div>
@endsection
