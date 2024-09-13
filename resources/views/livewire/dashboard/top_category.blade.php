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
                    <th>{{ __('category.category') }}</th>
                    <th class="text-right">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topCategorySummary as $key => $categorySummary)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>
                            {{ link_to_route('categories.show', Illuminate\Support\Str::limit($categorySummary->name, 28, ''), [
                                $categorySummary->id,
                                'start_date' => $year.'-01-01',
                                'end_date' => $year.'-12-31',
                            ]) }}
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
