@extends('layouts.guest')

@section('title', __('report.select_report'))

@section('content')

<div class="text-center mt-0 mb-2">
    <h1 class="page-title">{{ __('report.select_report') }}</h1>
</div>

<div class="row justify-content-center">
    @forelse ($books as $book)
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('book.book') }}: {{ $book->name }}</h3>
                </div>
                <div class="card-body">{{ $book->description }}</div>
                <div class="card-footer">
                    {{ link_to_route(
                        'public_reports.in_months',
                        __('report.view_report'),
                        ['active_book_id' => $book->id, 'nonce' => $book->nonce],
                        [
                            'id' => 'show-book-'.$book->id,
                            'class' => 'btn btn-success',
                        ]
                    ) }}
                </div>
            </div>
        </div>
    @empty
        {{ __('book.not_found') }}
    @endforelse
</div>
@endsection
