<div class="row mb-3" style="min-height: 8em;">
    <div class="col-md-3 text-center text-md-start">
        <h1 class="page-title">{{ __('partner.partner_type_donor') }}</h1>
        <div class="page-subtitle ms-0">
            {{ __('dashboard.dashboard') }} {{ __('donor.donor') }} {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-6 mt-3 mt-sm-0">
        {{ Form::open(['method' => 'get']) }}
        <div class="row g-2 justify-content-center mt-3 mx-3">
            <div class="col-auto">
                {{ Form::select('book_id', ['' => '-- '.__('book.all').' --'] + $availableBooks, optional($selectedBook)->id, ['class' => 'form-control me-1', 'onchange' => 'submit()']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('year', get_years(), $selectedYear, ['class' => 'form-control me-1', 'onchange' => 'submit()']) }}
            </div>
            <div class="col-auto">
                {{ Form::select('month', ['00' => '-- '.__('time.month').' --'] + get_months(), $selectedMonth, ['class' => 'form-control me-1', 'onchange' => 'submit()']) }}
            </div>
            <div class="col-auto text-center">
                @if ($selectedMonth == '00')
                    {{ link_to_route('donors.index', __('report.prev_year'), ['year' => $selectedYear - 1, 'month' => '00'], ['class' => 'btn btn-gray mt-2 me-1']) }}
                    {{ link_to_route('donors.index', __('report.this_year'), ['year' => today()->format('Y'), 'month' => '00'], ['class' => 'btn btn-gray mt-2 me-1']) }}
                    {{ link_to_route('donors.index', __('report.next_year'), ['year' => $selectedYear + 1, 'month' => '00'], ['class' => 'btn btn-gray mt-2 me-1']) }}
                    {{ link_to_route('donors.index', __('report.this_month'), [], ['class' => 'btn mt-2 me-1']) }}
                @else
                    @livewire('prev-month-button', ['routeName' => 'donors.index', 'buttonClass' => 'btn mt-2 me-1'])
                    {{ link_to_route('donors.index', __('report.this_month'), [], ['class' => 'btn mt-2 me-1']) }}
                    @livewire('next-month-button', ['routeName' => 'donors.index', 'buttonClass' => 'btn mt-2 me-1'])
                    {{ link_to_route('donors.index', __('report.this_year'), ['year' => today()->format('Y'), 'month' => '00'], ['class' => 'btn btn-gray mt-2 me-1']) }}
                @endif
                @can('create', new App\Transaction)
                    {{ link_to_route('donor_transactions.create', __('donor.add_donation'), [], ['class' => 'btn btn-success mt-2']) }}
                @endcan
            </div>
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-3 mt-3 mt-sm-0 text-center text-md-end">
        @livewire('donors.total-income-from-partner', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-2 mb-sm-0">
        @livewire('donors.donors-count', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
    <div class="col-md-4 mb-2 mb-sm-0">
        @livewire('donors.level-stats', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
    <div class="col-md-4 mb-2 mb-sm-0">
        @livewire('donors.income-stats', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
</div>
