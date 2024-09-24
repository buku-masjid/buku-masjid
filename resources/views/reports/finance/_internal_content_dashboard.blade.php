<div class="card">
    <div class="card-body p-3">
        <div class="text-muted mb-2 text-center">{{ __('transaction.balance') }}</div>
        <div class="table-responsive">
            @livewire('dashboard.balance-'.Illuminate\Support\Str::slug($book->report_periode_code), [
                'book' => $book,
                'year' => $year,
                'selectedMonth' => $month,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'isForPrint' => $isForPrint ?? false,
            ])
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-2 text-center">{{ __('dashboard.top_spending_category') }}</div>
                @livewire('dashboard.top-category', [
                    'book' => $book,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'isForPrint' => $isForPrint ?? false,
                    'typeCode' => 'spending',
                ])
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-2 text-center">{{ __('dashboard.top_income_category') }}</div>
                @livewire('dashboard.top-category', [
                    'book' => $book,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'isForPrint' => $isForPrint ?? false,
                    'typeCode' => 'income',
                ])
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-2 text-center">{{ __('dashboard.top_spending') }}</div>
                @livewire('dashboard.top-transaction', [
                    'book' => $book,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'isForPrint' => $isForPrint ?? false,
                    'typeCode' => 'spending',
                ])
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-2 text-center">{{ __('dashboard.top_income') }}</div>
                @livewire('dashboard.top-transaction', [
                    'book' => $book,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'isForPrint' => $isForPrint ?? false,
                    'typeCode' => 'income',
                ])
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="text-muted mb-2 text-center">{{ __('dashboard.daily_averages') }}</div>
                @livewire('dashboard.daily-averages', [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'isForPrint' => $isForPrint ?? false,
                    'book' => $book,
               ])
            </div>
        </div>
    </div>
</div>
