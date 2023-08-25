<div class="card table-responsive-sm">
    <table class="table table-sm table-hover">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th class="text-center">{{ __('time.date') }}</th>
                <th>{{ __('lecturing_schedule.time') }}</th>
                <th>{{ __('lecturing_schedule.friday_lecturer') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($lecturingSchedules[$audienceCode]))
                @foreach($lecturingSchedules[$audienceCode] as $key => $lecturingSchedule)
                <tr>
                    <td class="text-center">{{ 1 + $key }}</td>
                    <td class="text-center">{{ $lecturingSchedule->date_only }}</td>
                    <td>{{ $lecturingSchedule->start_time }}</td>
                    <td>{{ $lecturingSchedule->lecturer }}</td>
                    <td class="text-center">
                        @can('view', $lecturingSchedule)
                            {{ link_to_route(
                                'lecturing_schedules.show',
                                __('app.show'),
                                [$lecturingSchedule],
                                ['id' => 'show-lecturing_schedule-' . $lecturingSchedule->id]
                            ) }}
                        @endcan
                    </td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="6">{{ __('lecturing_schedule.friday_empty') }}</td></tr>
            @endif
        </tbody>
    </table>
</div>
