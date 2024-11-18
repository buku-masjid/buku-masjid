<div wire:init="getIncomeDashboardEntries">
    <div class="row mb-5">
        <div class="col-md-4 text-center text-md-left">
            <h1 class="page-title">Dashboard {{ __('time.year') }} {{ $year }}</h1>
        </div>
        <div class="col-md-4 mt-3 mt-sm-0 text-center">
        </div>
        <div class="col-md-4 mt-3 mt-sm-0">
            <select wire:model="year" class="form-control float-right" style="width: 6em">
                @foreach (get_years() as $yearNumber)
                    <option value="{{ $yearNumber }}">{{ $yearNumber }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="card">
            <table class="table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.partner_type_donor') }}</th>
                        @foreach (get_months() as $monthNumber => $monthName)
                            <th class="text-center">{{ Carbon\Carbon::parse($year.'-'.$monthNumber.'-01')->isoFormat('MMM') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @forelse ($incomeDashboardEntries->pluck('partner_name', 'partner_id') as $partnerId => $partnerName)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $partnerName }}</td>
                            @foreach (get_months() as $monthNumber => $monthName)
                                @php
                                    $incomeEntry = $incomeDashboardEntries->filter(function ($income) use ($year, $monthNumber, $partnerId) {
                                        return $income->tr_year_month == $year.'-'.$monthNumber && $income->partner_id == $partnerId;
                                    })->first();
                                @endphp
                                <td class="text-right">{{ $incomeEntry ? format_number($incomeEntry->total_amount) : '' }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr><td colspan="14">Tidak ada transaksi donatur tahun {{ $year }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
