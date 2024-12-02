<div class="row" style="min-height: 8em;">
    <div class="col-md-3 text-center text-md-left">
        <h1 class="page-title">{{ __('partner.partner_type_donor') }}</h1>
        <div class="page-subtitle ml-0">
            {{ __('dashboard.dashboard') }} {{ __('donor.donor') }} {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-6 mt-3 mt-sm-0">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline justify-content-center mt-3 mx-3']) }}
        {{ Form::select('book_id', ['' => '-- '.__('book.all').' --'] + $availableBooks, optional($selectedBook)->id, ['class' => 'form-control mr-1', 'onchange' => 'submit()']) }}
        {{ Form::select('year', get_years(), $selectedYear, ['class' => 'form-control mr-1', 'onchange' => 'submit()']) }}
        {{ Form::select('month', ['00' => '-- '.__('time.month').' --'] + get_months(), $selectedMonth, ['class' => 'form-control mr-1', 'onchange' => 'submit()']) }}
        <div class="form-group text-center">
            @if ($selectedMonth == '00')
                {{ link_to_route('donors.index', __('report.prev_year'), ['year' => $selectedYear - 1, 'month' => '00'], ['class' => 'btn btn-gray mt-2 mr-1']) }}
                {{ link_to_route('donors.index', __('report.this_year'), ['year' => today()->format('Y'), 'month' => '00'], ['class' => 'btn btn-gray mt-2 mr-1']) }}
                {{ link_to_route('donors.index', __('report.next_year'), ['year' => $selectedYear + 1, 'month' => '00'], ['class' => 'btn btn-gray mt-2 mr-1']) }}
                {{ link_to_route('donors.index', __('report.this_month'), [], ['class' => 'btn btn-secondary mt-2 mr-1']) }}
            @else
                @livewire('prev-month-button', ['routeName' => 'donors.index', 'buttonClass' => 'btn btn-secondary mt-2 mr-1'])
                {{ link_to_route('donors.index', __('report.this_month'), [], ['class' => 'btn btn-secondary mt-2 mr-1']) }}
                @livewire('next-month-button', ['routeName' => 'donors.index', 'buttonClass' => 'btn btn-secondary mt-2 mr-1'])
                {{ link_to_route('donors.index', __('report.this_year'), ['year' => today()->format('Y'), 'month' => '00'], ['class' => 'btn btn-gray mt-2 mr-1']) }}
            @endif
            @can('create', new App\Transaction)
                {{ link_to_route('donor_transactions.create', __('donor.add_donation'), [], ['class' => 'btn btn-success mt-2']) }}
            @endcan
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-3 mt-3 mt-sm-0 text-center text-md-right">
        @livewire('donors.total-income-from-partner', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        @livewire('donors.donors-count', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
    <div class="col-md-4">
        @livewire('donors.level-stats', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
    <div class="col-md-4">
        @livewire('donors.income-stats', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
</div>
