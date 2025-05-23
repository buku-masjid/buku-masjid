@if (request('action') == 'upload_files' && $transaction && !$isDiskFull)
    @can('update', $transaction)
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('transaction.upload_files') }}</h5>
                    {{ link_to_route('transactions.show', '', [$transaction], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => ['transactions.files.store', $transaction], 'files' => true]) !!}
                <div class="modal-body">
                    <div class="form-group mb-3 {{ $errors->has('files.*') ? 'has-error' : '' }}">
                        <label for="files" class="form-label fw-bold">{{ __('file.select') }} <span class="text-danger">*</span></label>
                        {{ Form::file('files[]', ['required' => true, 'multiple' => true, 'class' => 'form-control-file border p-2 rounded '.($errors->has('files.*') ? 'is-invalid' : ''), 'accept' => 'image/*']) }}
                        @if ($errors->has('files.*'))
                            @foreach ($errors->get('files.*') as $key => $errorMessages)
                                {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            @endforeach
                        @endif
                    </div>
                    {!! FormField::text('title', ['label' => __('app.description'), 'placeholder' => __('transaction.upload_file_placeholder')]) !!}
                    {!! FormField::textarea('description', ['label' => __('app.notes')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('file.upload'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('transactions.show', __('app.cancel'), [$transaction], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif
@if (request('action') == 'edit_file' && $editableFile)
    @can('update', $transaction)
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('file.edit') }}</h5>
                    {{ link_to_route('transactions.show', '', [$transaction], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableFile, ['route' => ['transactions.files.update', [$transaction, $editableFile]], 'method' => 'patch']) !!}
                <div class="modal-body">
                    @if (in_array($editableFile->type_code, ['raw_image', 'image']))
                        <a href="{{ asset('storage/'.$editableFile->file_path) }}" class="d-block mb-4">
                            <img src="{{ asset('storage/'.$editableFile->file_path) }}" alt="{{ $editableFile->title }}" class="img-fluid">
                        </a>
                    @endif
                    {!! FormField::text('title', ['label' => __('app.description'), 'placeholder' => __('transaction.upload_file_placeholder')]) !!}
                    {!! FormField::textarea('description', ['label' => __('app.notes')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('file.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('transactions.show', __('app.cancel'), [$transaction], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif
