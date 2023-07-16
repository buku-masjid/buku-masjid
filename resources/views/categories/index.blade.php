@extends('layouts.settings')

@section('title', __('category.list'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ __('category.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $categories->count() }} {{ __('category.category') }}</div>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
        {{ Form::label('book', __('book.book'), ['class' => 'control-label mr-1']) }}
        {{ Form::select('book_id', $books, $bookId, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0">
            {{ Form::submit(__('app.filter'), ['class' => 'btn btn-info mr-1']) }}
            @can('create', new App\Models\Category)
                {{ link_to_route('categories.index', __('category.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
            @endcan
        </div>
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <table class="table table-sm table-responsive-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('book.book') }}</th>
                        <th class="text-nowrap">{{ __('category.name') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                        <th>{{ __('category.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $key => $category)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $category->book->name }}</td>
                        <td class="text-nowrap">{!! $category->name_label !!}</td>
                        <td class="text-nowrap text-center">{{ $category->status }}</td>
                        <td>{{ $category->description }}</td>
                        <td class="text-center text-nowrap">
                            @can('view', $category)
                                {{ link_to_route(
                                    'categories.show',
                                    __('category.view_transactions'),
                                    $category,
                                    ['class' => 'btn btn-sm btn-secondary']
                                ) }}
                            @endcan
                            @can('update', $category)
                                {{ link_to_route(
                                    'categories.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $category->id],
                                    [
                                        'id' => 'edit-category-'.$category->id,
                                        'class' => 'btn btn-sm btn-warning',
                                    ]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">{{ __('category.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('categories.forms')
        @endif
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/bootstrap-colorpicker.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/bootstrap-colorpicker.min.js')) }}
<script>
(function () {
    $('#categoryModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('#color').colorpicker();
})();
</script>
@endpush
