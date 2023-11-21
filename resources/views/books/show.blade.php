@extends('layouts.settings')

@section('title', __('book.transactions'))

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $book->name }}</h1>
    <div class="page-subtitle">{{ __('book.transactions') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('books.index', __('book.back_to_index'), [], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-options">{{ $book->audience }}</span>
                {{ __('book.detail') }}
            </div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr><td class="col-4">{{ __('book.name') }}</td><td>{{ $book->name }}</td></tr>
                    <tr><td>{{ __('book.description') }}</td><td>{{ $book->description }}</td></tr>
                    <tr><td>{{ __('bank_account.bank_account') }}</td><td>{{ $book->bankAccount->name }}</td></tr>
                    <tr><td>{{ __('book.budget') }}</td><td>{{ $book->budget }}</td></tr>
                    <tr><td>{{ __('app.status') }}</td><td>{{ $book->status }}</td></tr>
                    <tr><td>{{ __('book.report_visibility') }}</td><td>{{ __('book.report_visibility_'.$book->report_visibility_code) }}</td></tr>
                    <tr><td>{{ __('report.periode') }}</td><td>{{ __('report.'.$book->report_periode_code) }}</td></tr>
                    <tr><td>{{ __('report.start_week_day') }}</td><td>{{ __('time.days.'.$book->start_week_day_code) }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('update', $book)
                    {{ link_to_route('books.edit', __('book.edit'), [$book], ['class' => 'btn btn-warning', 'id' => 'edit-book-'.$book->id]) }}
                @endcan
                {{ link_to_route('books.index', __('book.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>

@endsection
