<table class="table table-sm table-responsive-sm table-hover">
    <thead>
        <th class="text-center">{{ __('time.month') }}</th>
        <th class="text-center">{{ __('transaction.transaction') }}</th>
        <th class="text-right">{{ __('transaction.income') }}</th>
        <th class="text-right">{{ __('transaction.spending') }}</th>
        <th class="text-right">{{ __('transaction.difference') }}</th>
        <th class="text-center">{{ __('app.action') }}</th>
    </thead>
    <tbody>
        @foreach(get_months() as $monthNumber => $monthName)
        @php
            $any = isset($data[$monthNumber]);
        @endphp
        <tr>
            <td class="text-center">{{ month_id($monthNumber) }}</td>
            <td class="text-center">{{ $any ? $data[$monthNumber]->count : 0 }}</td>
            <td class="text-right text-nowrap">{{ format_number($income = ($any ? $data[$monthNumber]->income : 0)) }}</td>
            <td class="text-right text-nowrap">{{ format_number($spending = ($any ? $data[$monthNumber]->spending : 0)) }}</td>
            <td class="text-right text-nowrap">{{ format_number($difference = ($any ? $data[$monthNumber]->difference : 0)) }}</td>
            <td class="text-center text-nowrap">
                {{ link_to_route(
                    'transactions.index',
                    __('report.view_monthly'),
                    ['month' => $monthNumber, 'year' => $year, 'partner_id' => $partnerId, 'category_id' => $categoryId],
                    [
                        'class' => 'btn btn-secondary btn-sm',
                        'title' => __('report.monthly', ['year_month' => month_id($monthNumber).' '.$year]),
                    ]
                ) }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center">{{ __('app.total') }}</th>
            <th class="text-center">{{ $data->sum('count') }}</th>
            <th class="text-right">{{ format_number($data->sum('income')) }}</th>
            <th class="text-right">{{ format_number($data->sum('spending')) }}</th>
            <th class="text-right">{{ format_number($data->sum('difference')) }}</th>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>
