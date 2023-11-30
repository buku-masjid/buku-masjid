@if (request('action') == 'create')
@can('create', new App\Models\Book)
    <div id="bookModal" class="modal" role="dialog">
        <div class="modal-dialog">
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
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::select('bank_account_id', $bankAccounts, [
                                'label' => __('bank_account.bank_account'),
                                'placeholder' => __('book.no_bank_account'),
                            ]) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::price('budget', [
                                'label' => __('book.budget'),
                                'type' => 'number',
                                'currency' => config('money.currency_code'),
                                'step' => number_step()
                            ]) !!}
                        </div>
                    </div>
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
