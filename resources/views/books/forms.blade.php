@if (request('action') == 'create')
@can('create', new App\Models\Book)
    <div class="modal modal-blur show" id="book_modal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('book.create') }}</h5>
                    {{ link_to_route('books.index', '', [], ['class' => 'btn-close']) }}
                </div>
                {{ Form::open(['route' => 'books.store']) }}
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
                    {{ Form::submit(__('book.create'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('books.index', __('app.cancel'), [], ['class' => 'btn']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
@endcan
@endif
