<table class="table table-sm table-responsive-sm table-hover">
    <thead>
        <th class="text-center">{{ __('time.week') }}</th>
        <th class="text-center">{{ __('time.date') }}</th>
        <th class="text-center">{{ __('transaction.transaction') }}</th>
        <th class="text-right">{{ __('transaction.income') }}</th>
        <th class="text-right">{{ __('transaction.spending') }}</th>
        <th class="text-right">{{ __('transaction.difference') }}</th>
        <th class="text-center">{{ __('app.action') }}</th>
    </thead>
    <tbody>
        @foreach(get_week_numbers($year) as $weekNumber => $weekName)
        @php
            $any = isset($data[$weekNumber]);
        @endphp
        <tr>
            <td class="text-center">{{ $weekNumber }}</td>
            <td class="text-center text-nowrap">
                @php
                    $date = now();
                    $date->setISODate($year, $weekNumber);
                @endphp
                {{ $startDate = $loop->first ? $year.'-01-01' : $date->startOfWeek()->format('Y-m-d') }} -
                {{ $endDate = $loop->last ? $year.'-12-31' : $date->endOfWeek()->format('Y-m-d') }}
            </td>
            <td class="text-center">{{ $any ? $data[$weekNumber]->count : 0 }}</td>
            <td class="text-right text-nowrap">{{ format_number($income = ($any ? $data[$weekNumber]->income : 0)) }}</td>
            <td class="text-right text-nowrap">{{ format_number($spending = ($any ? $data[$weekNumber]->spending : 0)) }}</td>
            <td class="text-right text-nowrap">{{ format_number($difference = ($any ? $data[$weekNumber]->difference : 0)) }}</td>
            <td class="text-center text-nowrap">
                {{ link_to_route(
                    'transaction_search.index',
                    __('report.view_weekly'),
                    [
                        'search_query' => '---',
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'partner_id' => $partnerId,
                        'category_id' => $categoryId
                    ],
                    [
                        'class' => 'btn btn-secondary btn-sm',
                        'title' => __('report.weekly', ['year_week' => $year.'-'.$weekNumber]),
                    ]
                ) }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center" colspan="2">{{ __('app.total') }}</th>
            <th class="text-center">{{ $data->sum('count') }}</th>
            <th class="text-right">{{ format_number($data->sum('income')) }}</th>
            <th class="text-right">{{ format_number($data->sum('spending')) }}</th>
            <th class="text-right">{{ format_number($data->sum('difference')) }}</th>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>
