<div wire:init="getMaritalStatuses">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="row align-items-end">
            <div class="col-12 col-sm-8">
                <div class="h3">{{ __('partner.marital_status') }}</div>
            </div>
            <div class="col-12 col-sm-4 text-right mb-2"></div>
        </div>

        <div class="card table-responsive-sm">
            <table class="table-sm table-striped table-bordered small">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.marital_status') }}</th>
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
                    @foreach (__('partner.marital_statuses') as $statusId => $statusName)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $statusName }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $maritalStatusCount = $maritalStatuses->filter(function ($maritalStatus) use ($statusId, $genderCode) {
                                        return $maritalStatus->gender_code == $genderCode && $maritalStatus->marital_status_id == $statusId;
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $maritalStatusCount, ['gender_code' => $genderCode, 'marital_status_id' => $statusId, 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $maritalStatusCount = $maritalStatuses->filter(function ($maritalStatus) use ($statusId) {
                                    return $maritalStatus->marital_status_id == $statusId;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $maritalStatusCount, ['marital_status_id' => $statusId, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $unknownMaritalStatusCount = $maritalStatuses->filter(function ($maritalStatus) use ($statusId) {
                            return is_null($maritalStatus->marital_status_id);
                        })->sum('partners_count');
                    @endphp
                    @if ($unknownMaritalStatusCount)
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ __('app.unknown') }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $maritalStatusCount = $maritalStatuses->filter(function ($maritalStatus) use ($statusId, $genderCode) {
                                        return $maritalStatus->gender_code == $genderCode && is_null($maritalStatus->marital_status_id);
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $maritalStatusCount, ['gender_code' => $genderCode, 'marital_status_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $maritalStatusCount = $maritalStatuses->filter(function ($maritalStatus) use ($statusId) {
                                    return is_null($maritalStatus->marital_status_id);
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $maritalStatusCount, ['marital_status_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                        @foreach ($genders as $genderCode => $genderName)
                            @php
                                $maritalStatusCount = $maritalStatuses->filter(function ($maritalStatus) use ($genderCode) {
                                    return $maritalStatus->gender_code == $genderCode;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $maritalStatusCount, ['gender_code' => $genderCode, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{ link_to_route('partners.search', $maritalStatuses->sum('partners_count'), ['type_code' => $partnerTypeCode]) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
