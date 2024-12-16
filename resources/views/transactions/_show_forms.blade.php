@if (request('action') == 'upload_files' && $transaction)
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
                    {!! FormField::file('file') !!}
                    {!! FormField::textarea('description') !!}
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
