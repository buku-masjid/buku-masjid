@if (request('action') == 'create')
@can('create', new App\Models\Category)
    <div class="modal modal-blur show" id="category_modal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('category.create') }}</h5>
                    {{ link_to_route('categories.index', '', [], ['class' => 'btn-close']) }}
                </div>
                {{ Form::open(['route' => 'categories.store']) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            {!! FormField::text('name', ['required' => true, 'label' => __('category.name')]) !!}
                        </div>
                        <div class="col-md-4">
                            {!! FormField::radios(
                                'color',
                                [
                                    config('masjid.income_color') => '<span class="badge text-azure-fg" style="background-color: '.config('masjid.income_color').'">'.__('transaction.income').'</span>',
                                    config('masjid.spending_color') => '<span class="badge text-azure-fg" style="background-color: '.config('masjid.spending_color').'">'.__('transaction.spending').'</span>',
                                ],
                                ['required' => true, 'label' => __('category.color'), 'list_style' => 'unstyled']
                            ) !!}
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('category.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('category.create'), ['class' => 'btn btn-success']) !!}
                    {{ Form::hidden('book_id', auth()->activeBookId()) }}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
@endcan
@endif

@if (request('action') == 'edit' && $editableCategory)
@can('update', $editableCategory)
    <div class="modal modal-blur show" id="category_modal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('category.edit') }}</h5>
                    {{ link_to_route('categories.index', '', [], ['class' => 'btn-close']) }}
                </div>
                {{ Form::model($editableCategory, ['route' => ['categories.update', $editableCategory], 'method' => 'patch']) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            {!! FormField::text('name', ['required' => true, 'label' => __('category.name')]) !!}
                        </div>
                        <div class="col-md-4">
                            {!! FormField::radios(
                                'color',
                                [
                                    '#00AABB' => '<span class="badge text-azure-fg" style="background-color: #00AABB">'.__('transaction.income').'</span>',
                                    '#F16867' => '<span class="badge text-azure-fg" style="background-color: #F16867">'.__('transaction.spending').'</span>',
                                ],
                                ['required' => true, 'label' => __('category.color'), 'list_style' => 'unstyled']
                            ) !!}
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('category.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::radios('status_id', [App\Models\Category::STATUS_INACTIVE => __('app.inactive'), App\Models\Category::STATUS_ACTIVE => __('app.active')], ['label' => __('app.status')]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::radios('report_visibility_code', [
                                App\Models\Category::REPORT_VISIBILITY_PUBLIC => __('category.report_visibility_public'),
                                App\Models\Category::REPORT_VISIBILITY_INTERNAL => __('category.report_visibility_internal')
                            ], ['label' => __('category.report_visibility')]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{ Form::hidden('book_id', auth()->activeBookId()) }}
                    {!! Form::submit(__('category.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn']) }}
                    @can('delete', $editableCategory)
                        {!! link_to_route(
                            'categories.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editableCategory->id],
                            ['id' => 'del-category-'.$editableCategory->id, 'class' => 'btn btn-danger float-start']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
@endcan
@endif

@if (request('action') == 'delete' && $editableCategory)
@can('delete', $editableCategory)
    <div class="modal modal-blur show" id="category_modal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('category.delete') }} {{ $editableCategory->type }}</h5>
                    {{ link_to_route('categories.index', '', [], ['class' => 'btn-close']) }}
                </div>
                {{ Form::open(['url' => route('categories.destroy', $editableCategory), 'method' => 'DELETE']) }}
                <div class="modal-body">
                    <label class="form-label">{{ __('category.name') }}</label>
                    <p>{!! $editableCategory->name_label !!}</p>
                    <label class="form-label">{{ __('category.description') }}</label>
                    <p>{{ $editableCategory->description }}</p>
                    <label class="form-label">{{ __('book.book') }}</label>
                    <p>{{ optional($editableCategory->book)->name }}</p>
                    {!! $errors->first('category_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">
                    {!! Form::hidden('category_id', $editableCategory->id) !!}
                    {!! Form::checkbox('delete_transactions', 1, false, ['id' => 'delete_transactions']) !!}
                    {!! Form::label('delete_transactions', __('category.delete_transactions')) !!}
                    <br>
                    {{ __('app.delete_confirm') }}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('app.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
@endcan
@endif
