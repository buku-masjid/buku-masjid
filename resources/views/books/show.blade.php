@extends('layouts.settings')

@section('title', __('book.detail').' - '.$book->name)

@section('content_settings')

<div class="page-header">
    <h1 class="page-title">{{ $book->name }}</h1>
    <div class="page-subtitle">{{ __('book.detail') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('books.index', __('book.back_to_index'), [], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('book.detail') }}</div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr><td class="col-4">{{ __('book.name') }}</td><td>{{ $book->name }}</td></tr>
                    <tr>
                        <td>{{ __('book.management_title') }}</td>
                        <td>{{ Setting::for($book)->get('management_title', __('report.management')) }}</td>
                    </tr>
                    <tr><td>{{ __('book.description') }}</td><td>{{ $book->description }}</td></tr>
                    <tr><td>{{ __('bank_account.bank_account') }}</td><td>{{ $book->bankAccount->name }}</td></tr>
                    <tr><td>{{ __('book.budget') }}</td><td>{{ format_number($book->budget ?: 0) }}</td></tr>
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
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">{{ __('report.finance_summary') }}</div>
            <div class="page-options d-flex"></div>
            @livewire('books.financial-summary', ['bookId' => $book->id])
        </div>
        <div class="card">
            <div class="card-header">{{ __('report.signatures') }}</div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr><th colspan="3">{{ __('app.left_part') }}</th></tr>
                    <tr>
                        <td>{{ __('report.acknowledgment_text') }}</td>
                        <td>{{ Setting::for($book)->get('acknowledgment_text_left') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_position') }}</td>
                        <td>{{ Setting::for($book)->get('sign_position_left') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_name') }}</td>
                        <td>{{ Setting::for($book)->get('sign_name_left') }}</td>
                    </tr>
                    <tr><th colspan="3">{{ __('app.mid_part') }}</th></tr>
                    <tr>
                        <td>{{ __('report.acknowledgment_text') }}</td>
                        <td>{{ Setting::for($book)->get('acknowledgment_text_mid') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_position') }}</td>
                        <td>{{ Setting::for($book)->get('sign_position_mid') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_name') }}</td>
                        <td>{{ Setting::for($book)->get('sign_name_mid') }}</td>
                    </tr>
                    <tr><th colspan="3">{{ __('app.right_part') }}</th></tr>
                    <tr>
                        <td>{{ __('report.acknowledgment_text') }}</td>
                        <td>{{ Setting::for($book)->get('acknowledgment_text_right') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_position') }}</td>
                        <td>{{ Setting::for($book)->get('sign_position_right') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('report.sign_name') }}</td>
                        <td>{{ Setting::for($book)->get('sign_name_right') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
