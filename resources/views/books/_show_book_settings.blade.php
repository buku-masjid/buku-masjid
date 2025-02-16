<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('book.detail') }}</div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr><td class="col-5">{{ __('book.name') }}</td><td>{{ $book->name }}</td></tr>
                    <tr>
                        <td>{{ __('book.management_title') }}</td>
                        <td>{{ Setting::for($book)->get('management_title', __('report.management')) }}</td>
                    </tr>
                    <tr><td>{{ __('book.manager') }}</td><td>{{ $book->manager->name }}</td></tr>
                    <tr><td>{{ __('book.description') }}</td><td>{{ $book->description }}</td></tr>
                    <tr><td>{{ __('bank_account.bank_account') }}</td><td>{{ $book->bankAccount->name }}</td></tr>
                    <tr><td>{{ __('book.budget') }}</td><td>{{ format_number($book->budget ?: 0) }}</td></tr>
                    <tr><td>{{ __('app.status') }}</td><td>{{ $book->status }}</td></tr>
                    <tr><td>{{ __('report.periode') }}</td><td>{{ __('report.'.$book->report_periode_code) }}</td></tr>
                    <tr><td>{{ __('report.start_week_day') }}</td><td>{{ __('time.days.'.$book->start_week_day_code) }}</td></tr>
                    <tr><td>{{ __('report.has_pdf_page_number') }}</td><td>{{ $book->start_week_day_code == '0' ? __('app.no') : __('app.yes') }}</td></tr>
                    <tr>
                        <td>{{ __('book.income_partners') }}</td>
                        <td>
                            {{ Setting::for($book)->get('income_partner_codes') }}
                            (default: {{ Setting::for($book)->get('income_partner_null') ?: config('partners.income_default_value') }})
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('book.spending_partners') }}</td>
                        <td>
                            {{ Setting::for($book)->get('spending_partner_codes') }}
                            (default: {{ Setting::for($book)->get('spending_partner_null') ?: config('partners.spending_default_value') }})
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('update', $book)
                    {{ link_to_route('books.edit', __('book.edit'), [$book], ['class' => 'btn btn-warning text-dark', 'id' => 'edit-book-'.$book->id]) }}
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
            <div class="card-header">{{ __('book.visibility') }}</div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr>
                        <td class="col-7">{{ __('book.report_visibility') }}</td>
                        <td>{{ __('book.report_visibility_'.$book->report_visibility_code) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('book.transaction_files_visibility') }}</td>
                        <td>{{ __('book.report_visibility_'.Setting::for($book)->get('transaction_files_visibility_code', App\Models\Book::REPORT_VISIBILITY_INTERNAL)) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
