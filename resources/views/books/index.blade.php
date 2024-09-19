@extends('layouts.settings')

@section('title', __('book.list'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ __('book.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $books->total() }} {{ __('book.book') }}</div>
    <div class="page-options d-flex">
        @can('create', new App\Models\Book)
            {{ link_to_route('books.index', __('book.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <table class="table table-sm table-responsive-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('book.name') }}</th>
                        <th class="text-right text-nowrap">{{ __('book.budget') }}</th>
                        <th class="text-nowrap">{{ __('report.periode') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                        <th class="text-center">{{ __('book.visibility') }}</th>
                        <th>{{ __('bank_account.bank_account') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $key => $book)
                    <tr>
                        <td class="text-center">{{ $key + $books->firstItem() }}</td>
                        <td class="text-nowrap">
                            @can('view', $book)
                                {{ link_to_route('books.show', $book->name, $book, [
                                    'id' => 'show-book-'.$book->id,
                                    'title' => __('book.show'),
                                ]) }}
                            @else
                                {{ $book->name }}
                            @endcan
                        </td>
                        <td class="text-nowrap text-right">{{ $book->budget ? format_number($book->budget) : '' }}</td>
                        <td class="text-nowrap">{{ __('report.'.$book->report_periode_code) }}</td>
                        <td class="text-nowrap text-center">{{ $book->status }}</td>
                        <td class="text-center">{{ __('book.report_visibility_'.$book->report_visibility_code) }}</td>
                        <td>{{ $book->bankAccount->name }}</td>
                        <td class="text-center text-nowrap">
                            @if ($book->id != auth()->activeBookId())
                                {!! FormField::formButton(
                                    ['route' => 'book_switcher.store'],
                                    __('book.switch'),
                                    ['id' => 'activate_book_'.$book->id, 'class' => 'btn btn-success btn-sm'],
                                    ['switch_book' => $book->id]
                                ) !!}
                            @else
                                <span class="btn btn-secondary btn-sm disabled">{{ __('app.active') }}</span>
                            @endif
                            @can('view', $book)
                                {{ link_to_route(
                                    'books.show',
                                    __('app.show'),
                                    [$book],
                                    [
                                        'id' => 'show-book-'.$book->id,
                                        'class' => 'btn btn-sm btn-secondary',
                                    ]
                                ) }}
                            @endcan
                            @can('update', $book)
                                {{ link_to_route(
                                    'books.edit',
                                    __('app.edit'),
                                    [$book],
                                    [
                                        'id' => 'edit-book-'.$book->id,
                                        'class' => 'btn btn-sm text-dark btn-warning',
                                    ]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">{{ __('book.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $books->links() }}
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('books.forms')
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    $('#bookModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
