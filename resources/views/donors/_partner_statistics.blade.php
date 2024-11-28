<div class="row" style="min-height: 8em;">
    <div class="col-md-3 text-center text-md-left">
        <h1 class="page-title">{{ __('partner.partner_type_donor') }}</h1>
        <div class="page-subtitle ml-0">
            {{ __('transaction.income') }} {{ __('donor.donor') }} {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-6 mt-3 mt-sm-0">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline justify-content-center mt-3 mx-3']) }}
        {{ Form::select('book_id', ['' => '-- '.__('book.all').' --'] + $availableBooks, optional($selectedBook)->id, ['class' => 'form-control mr-1']) }}
        {{ Form::select('month', ['00' => '-- '.__('time.month').' --'] + get_months(), $selectedMonth, ['class' => 'form-control mr-1']) }}
        {{ Form::select('year', ['0000' => '-- '.__('time.year').' --'] + get_years(), $selectedYear, ['class' => 'form-control mr-1']) }}
        <div class="form-group mt-4 mt-sm-0 pt-0 pt-sm-2">
            {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-2']) }}
            {{ link_to_route('donors.index', __('app.reset'), [], ['class' => 'btn btn-secondary mr-2']) }}
            @can('create', new App\Transaction)
                {{ link_to_route('donor_transactions.create', __('donor.add_donation'), [], ['class' => 'btn btn-success']) }}
            @endcan
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-3 mt-3 mt-sm-0 text-center text-md-right">
        @livewire('donors.total-income-from-partner', ['book' => $selectedBook, 'year' => $selectedYear])
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        @livewire('donors.donors-count', ['book' => $selectedBook, 'year' => $selectedYear])
    </div>
    <div class="col-md-4">
        @livewire('donors.level-stats', ['book' => $selectedBook, 'year' => $selectedYear])
    </div>
    <div class="col-md-4">
        @livewire('donors.gender-stats', ['book' => $selectedBook, 'year' => $selectedYear])
    </div>
</div>
@livewire('donors.income-from-partner-graph', ['book' => $selectedBook, 'year' => $selectedYear])
