@extends('layouts.settings')

@section('title', __('book.landing_page').' - '.$book->name)

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $book->name }}</h1>
    <div class="page-subtitle">{{ __('book.landing_page') }}</div>
    <div class="page-options d-flex">
        @can('update', $book)
            {{ link_to_route('books.landing_page.edit', __('app.edit'), [$book], ['class' => 'btn btn-warning text-dark mr-2', 'id' => 'edit_landing_page-book-'.$book->id]) }}
        @endcan
        {{ link_to_route('books.show', __('app.back'), [$book], ['class' => 'btn btn-secondary']) }}
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('book.landing_page') }}</div>
            <div class="card-body">
                @if (Setting::for($book)->get('poster_image'))
                    <img class="img-fluid my-4" src="{{ Storage::url(Setting::for($book)->get('poster_image')) }}" alt="{{ $book->name }}">
                @else
                    <div class="p-4">{{ __('book.poster_image') }}</div>
                @endif
            </div>
            <div class="card-body">
                {{ __('book.due_date') }}: {{ Setting::for($book)->get('due_date') }}
            </div>
            <div class="card-body">
                {{ Setting::for($book)->get('landing_page_content') }}
            </div>
        </div>
    </div>
</div>

@endsection
