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
                    <th>{{ __('category.name') }}</th>
                    <th class="text-right">{{ __('transaction.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topCategorySummary as $key => $categorySummary)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $categorySummary->name }}</td>
                        <td class="text-right">
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
