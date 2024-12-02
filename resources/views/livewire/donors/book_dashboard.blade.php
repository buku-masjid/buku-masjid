<div wire:init="getBookDashboardEntries" class="row justify-content-center">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="col-12 {{ isset(get_months()[$month]) ? 'col-lg-6' : '' }}">
            @forelse ($bookDashboardEntries as $trYear => $bookDashboardEntriesPerYear)
                <div class="row align-items-end">
                    <div class="col-12 col-sm-8">
                        <div class="h3">
                            {{ __('transaction.income') }} {{ __('book.book') }}
                            {{ optional($book)->name }}
                            {{ get_months()[$month] ?? '' }}
                            {{ $trYear }}
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 text-right mb-2">
                        <span class="text-muted">({{ __('report.in_thousand') }} {{ config('money.currency_text') }})</span>
                    </div>
                </div>

                <div class="card table-responsive-sm">
                    <table class="table-sm table-striped table-bordered small">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 3em">{{ __('app.table_no') }}</th>
                                <th style="width: 20em">{{ __('book.name') }}</th>
                                @unless (isset(get_months()[$month]))
                                    @foreach (get_months() as $monthNumber => $monthName)
                                        <th class="text-center" style="width: 5em">{{ Carbon\Carbon::parse($trYear.'-'.$monthNumber.'-01')->isoFormat('MMM') }}</th>
                                    @endforeach
                                @endunless
                                <th class="text-center">{{ __('app.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($availableBooks[$trYear] as $availableBook)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td style="min-width: 14em">
                                        {{ link_to_route('donors.index', $availableBook->name, ['book_id' => $availableBook->id, 'year' => $trYear, 'month' => $month]) }}
                                    </td>
                                    @unless (isset(get_months()[$month]))
                                        @foreach (get_months() as $monthNumber => $monthName)
                                            @php
                                                $bookEntry = $bookDashboardEntriesPerYear->filter(function ($bookEntry) use ($trYear, $monthNumber, $availableBook) {
                                                    return $bookEntry->tr_year_month == $trYear.'-'.$monthNumber && $bookEntry->book_id == $availableBook->id;
                                                })->first();
                                            @endphp
                                            <td class="text-right">{{ $bookEntry ? format_number($bookEntry->total_amount / 1000) : '' }}</td>
                                        @endforeach
                                    @endunless

                                    @php
                                        $bookTotal = $bookDashboardEntriesPerYear->filter(function ($bookEntry) use ($availableBook) {
                                            return $bookEntry->book_id == $availableBook->id;
                                        })->sum('total_amount');
                                    @endphp
                                    <td class="text-right">{{ format_number($bookTotal / 1000) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="strong">
                                <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                                @if (isset(get_months()[$month]))
                                @else
                                    @foreach (get_months() as $monthNumber => $monthName)
                                        @php
                                            $monthTotal = $bookDashboardEntriesPerYear->filter(function ($bookEntry) use ($trYear, $monthNumber) {
                                                return $bookEntry->tr_year_month == $trYear.'-'.$monthNumber;
                                            })->sum('total_amount');
                                        @endphp
                                        <td class="text-right">{{ format_number($monthTotal / 1000) }}</td>
                                    @endforeach
                                @endif
                                <td class="text-right">{{ format_number($bookDashboardEntriesPerYear->sum('total_amount') / 1000) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @empty
                <div>
                    {{ __('transaction.not_found') }}
                    {{ optional($book)->name }}
                    {{ get_months()[$month] ?? '' }}
                    {{ $year != '0000' ? $year : '' }}
                </div>
            @endforelse
        </div>
    @endif
</div>
