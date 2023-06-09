@if (request('action') == 'create')
@can('create', new App\Category)
    <div id="categoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('category.create') }}</h5>
                    {{ link_to_route('categories.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'categories.store']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('name', ['required' => true, 'label' => __('category.name')]) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label for="color" class="control-label">{{ __('category.color') }}</label>
                                <div id="color" class="input-group colorpicker-component">
                                    <input name="color" type="text" value="#00AABB" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('category.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('category.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editableCategory)
@can('update', $editableCategory)
    <div id="categoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('category.edit') }}</h5>
                    {{ link_to_route('categories.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableCategory, ['route' => ['categories.update', $editableCategory], 'method' => 'patch']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('name', ['required' => true, 'label' => __('category.name')]) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label for="color" class="control-label">{{ __('category.color') }}</label>
                                <div id="color" class="input-group colorpicker-component">
                                    <input name="color" type="text" value="{{ $editableCategory->color }}" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('category.description')]) !!}
                    {!! FormField::radios('status_id', [App\Category::STATUS_INACTIVE => __('app.inactive'), App\Category::STATUS_ACTIVE => __('app.active')], ['label' => __('app.status')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('category.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableCategory)
                        {!! link_to_route(
                            'categories.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editableCategory->id],
                            ['id' => 'del-category-'.$editableCategory->id, 'class' => 'btn btn-danger float-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'delete' && $editableCategory)
@can('delete', $editableCategory)
    <div id="categoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('category.delete') }} {{ $editableCategory->type }}</h5>
                    {{ link_to_route('categories.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['url' => route('categories.destroy', $editableCategory), 'method' => 'DELETE']) !!}
                <div class="modal-body">
                    <label class="control-label">{{ __('category.name') }}</label>
                    <p>{!! $editableCategory->name_label !!}</p>
                    <label class="control-label">{{ __('category.description') }}</label>
                    <p>{{ $editableCategory->description }}</p>
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
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endcan
@endif
