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
        <div class="card table-responsive-sm">
            <table class="table-sm table-striped table-bordered small">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.partner_type_donor') }}</th>
                        @foreach (get_months() as $monthNumber => $monthName)
                            <th class="text-center">{{ Carbon\Carbon::parse($year.'-'.$monthNumber.'-01')->isoFormat('MMM') }}</th>
                        @endforeach
                        <th class="text-center">{{ __('app.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @forelse ($availablePartners as $partner)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                {{ link_to_route('donors.show', $partner->name, $partner->id) }}
                                <a href="https://wa.me/{{ str_replace([' ', '+', '(', ')'], '', $partner->phone) }}">
                                    <i class="fe fe-phone-outgoing float-right"></i>
                                </a>
                            </td>
                            @foreach (get_months() as $monthNumber => $monthName)
                                @php
                                    $incomeEntry = $incomeDashboardEntries->filter(function ($income) use ($year, $monthNumber, $partner) {
                                        return $income->tr_year_month == $year.'-'.$monthNumber && $income->partner_id == $partner->id;
                                    })->first();
                                @endphp
                                <td class="text-right">{{ $incomeEntry ? format_number($incomeEntry->total_amount) : '' }}</td>
                            @endforeach

                            @php
                                $incomeTotal = $incomeDashboardEntries->filter(function ($income) use ($partner) {
                                    return $income->partner_id == $partner->id;
                                })->sum('total_amount');
                            @endphp
                            <td class="text-right">{{ format_number($incomeTotal) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="15">Tidak ada transaksi donatur tahun {{ $year }}.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                        @foreach (get_months() as $monthNumber => $monthName)
                            @php
                                $monthTotal = $incomeDashboardEntries->filter(function ($income) use ($year, $monthNumber) {
                                    return $income->tr_year_month == $year.'-'.$monthNumber;
                                })->sum('total_amount');
                            @endphp
                            <td class="text-right">{{ format_number($monthTotal) }}</td>
                        @endforeach
                        <td class="text-right">{{ format_number($incomeDashboardEntries->sum('total_amount')) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
