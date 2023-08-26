<div class="card table-responsive-sm">
    <table class="table table-sm table-hover">
        <thead>
            <tr>
                <th class="text-center">{{ __('app.table_no') }}</th>
                <th>{{ __('time.day_name') }}</th>
                <th class="text-center">{{ __('time.date') }}</th>
                <th>{{ __('time.time') }}</th>
                <th>{{ __('lecturing_schedule.lecturer') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($lecturingSchedules[$audienceCode]))
                @foreach($lecturingSchedules[$audienceCode] as $key => $lecturingSchedule)
                <tr>
                    <td class="text-center">{{ 1 + $key }}</td>
                    <td>{{ $lecturingSchedule->day_name }}</td>
                    <td class="text-center">{{ $lecturingSchedule->full_date }}</td>
                    <td>
                        {{ $lecturingSchedule->time_text ? $lecturingSchedule->time_text.', ' : '' }}
                        {{ $lecturingSchedule->time }}
                    </td>
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
                <tr><td colspan="7">{{ __('lecturing_schedule.empty') }}</td></tr>
            @endif
        </tbody>
    </table>
</div>
