<div wire:init="getTopCategorySummary">
    @if ($isLoading)
        <div class="loading-state text-center">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>{{ __('app.table_no') }}</th>
                    <th style="width: 70%" class="text-left">{{ __('category.category') }}</th>
                    <th style="width: 25%" class="text-right">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topCategorySummary as $key => $categorySummary)
                    <tr>
                        <td class="text-center">{{ ++$key }}</td>
                        <td>
                            @if ($isForPrint)
                                {{ $categorySummary->name }}
                            @else
                                {{ link_to_route('categories.show', Illuminate\Support\Str::limit($categorySummary->name, 28, ''), [
                                    $categorySummary->id,
                                    'start_date' => $startDate,
                                    'end_date' => $endDate,
                                ]) }}
                            @endif
                        </td>
                        <td class="text-right" style="color: {{ config('masjid.'.$typeCode.'_color') }}">
                            @if ($categorySummary->transactions_sum_amount)
                                {{ format_number($categorySummary->transactions_sum_amount) }}
                            @else
                                0
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
