<div wire:init="getActivityStatuses">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="row align-items-end">
            <div class="col-12 col-sm-8">
                <div class="h3">{{ __('partner.activity_status') }}</div>
            </div>
            <div class="col-12 col-sm-4 text-right mb-2"></div>
        </div>

        <div class="card table-responsive-sm">
            <table class="table-sm table-striped table-bordered small">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.activity_status') }}</th>
                        @foreach ($genders as $genderCode => $genderName)
                            <th class="text-center">{{ $genderName }}</th>
                        @endforeach
                        <th class="text-center">{{ __('app.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach (__('partner.activity_statuses') as $statusId => $statusName)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $statusName }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $activityStatusCount = $activityStatuses->filter(function ($activityStatus) use ($statusId, $genderCode) {
                                        return $activityStatus->gender_code == $genderCode && $activityStatus->activity_status_id == $statusId;
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $activityStatusCount, ['gender_code' => $genderCode, 'activity_status_id' => $statusId, 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $activityStatusCount = $activityStatuses->filter(function ($activityStatus) use ($statusId) {
                                    return $activityStatus->activity_status_id == $statusId;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $activityStatusCount, ['activity_status_id' => $statusId, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $unknownActivityStatusCount = $activityStatuses->filter(function ($activityStatus) use ($statusId) {
                            return is_null($activityStatus->activity_status_id);
                        })->sum('partners_count');
                    @endphp
                    @if ($unknownActivityStatusCount)
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ __('app.unknown') }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $activityStatusCount = $activityStatuses->filter(function ($activityStatus) use ($statusId, $genderCode) {
                                        return $activityStatus->gender_code == $genderCode && is_null($activityStatus->activity_status_id);
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $activityStatusCount, ['gender_code' => $genderCode, 'activity_status_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            <td class="text-center">
                                {{ link_to_route('partners.search', $unknownActivityStatusCount, ['activity_status_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                        @foreach ($genders as $genderCode => $genderName)
                            @php
                                $activityStatusCount = $activityStatuses->filter(function ($activityStatus) use ($genderCode) {
                                    return $activityStatus->gender_code == $genderCode;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $activityStatusCount, ['gender_code' => $genderCode, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{ link_to_route('partners.search', $activityStatuses->sum('partners_count'), ['type_code' => $partnerTypeCode]) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
