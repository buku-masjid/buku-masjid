@if (request('action') == 'create')
@can('create', new App\Models\Book)
    <div id="bookModal" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.create') }}</h5>
                    {{ link_to_route('books.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'books.store']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('book.name')]) !!}
                    {!! FormField::textarea('description', ['label' => __('book.description')]) !!}
                    {!! FormField::select('bank_account_id', $bankAccounts, [
                        'label' => __('bank_account.bank_account'),
                        'placeholder' => __('book.no_bank_account')
                    ]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('book.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('books.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editableBook)
@can('update', $editableBook)
    <div id="bookModal" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.edit') }}</h5>
                    {{ link_to_route('books.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableBook, ['route' => ['books.update', $editableBook], 'method' => 'patch']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('book.name')]) !!}
                    {!! FormField::textarea('description', ['label' => __('book.description')]) !!}
                    {!! FormField::select('bank_account_id', $bankAccounts, [
                        'label' => __('bank_account.bank_account'),
                        'placeholder' => __('book.no_bank_account'),
                    ]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::radios('status_id', [
                                App\Models\Book::STATUS_INACTIVE => __('app.inactive'),
                                App\Models\Book::STATUS_ACTIVE => __('app.active')
                            ], ['label' => __('app.status')]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::radios('report_visibility_code', [
                                App\Models\Book::REPORT_VISIBILITY_PUBLIC => __('category.report_visibility_public'),
                                App\Models\Book::REPORT_VISIBILITY_INTERNAL => __('category.report_visibility_internal')
                            ], ['label' => __('category.report_visibility')]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('book.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('books.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableBook)
                        {!! link_to_route(
                            'books.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editableBook->id],
                            ['id' => 'del-book-'.$editableBook->id, 'class' => 'btn btn-danger float-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'delete' && $editableBook)
@can('delete', $editableBook)
    <div id="bookModal" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.delete') }} {{ $editableBook->type }}</h5>
                    {{ link_to_route('books.index', '', [], ['class' => 'close']) }}
                </div>
                <div class="modal-body">
                    <label class="control-label">{{ __('book.name') }}</label>
                    <p>{!! $editableBook->name_label !!}</p>
                    <label class="control-label">{{ __('book.description') }}</label>
                    <p>{{ $editableBook->description }}</p>
                    <label class="control-label">{{ __('bank_account.bank_account') }}</label>
                    <p>{{ optional($editableBook->bankAccount)->name }}</p>
                    {!! $errors->first('book_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body bg-warning">
                    <div class="row">
                        <div class="col-1"><i class="fe fe-alert-circle"></i></div>
                        <div class="col-11">{!! __('book.delete_confirm') !!}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['books.destroy', $editableBook], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class'=>'btn btn-danger'],
                        [
                            'book_id' => $editableBook->id,
                        ]
                    ) !!}
                    {{ link_to_route('books.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        </div>
    </div>
@endcan
@endif
