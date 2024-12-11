<div wire:init="getAgeGroups">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="row align-items-end">
            <div class="col-12 col-sm-8">
                <div class="h3">{{ __('partner.age_group') }}</div>
            </div>
            <div class="col-12 col-sm-4 text-right mb-2"></div>
        </div>

        <div class="card table-responsive-sm">
            <table class="table-sm table-striped table-bordered small">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.age_group') }}</th>
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
                    @foreach (__('partner.age_groups') as $groupCode => $groupName)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $groupName }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $ageGroupCount = $ageGroups->filter(function ($ageGroup) use ($groupCode, $genderCode) {
                                        return $ageGroup->gender_code == $genderCode && $ageGroup->age_group_code == $groupCode;
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $ageGroupCount, ['gender_code' => $genderCode, 'age_group_code' => $groupCode, 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $ageGroupCount = $ageGroups->filter(function ($ageGroup) use ($groupCode) {
                                    return $ageGroup->age_group_code == $groupCode;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $ageGroupCount, ['age_group_code' => $groupCode, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $unknownMaritalStatusCount = $ageGroups->filter(function ($ageGroup) use ($groupCode) {
                            return is_null($ageGroup->age_group_code);
                        })->sum('partners_count');
                    @endphp
                    @if ($unknownMaritalStatusCount)
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ __('app.unknown') }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $ageGroupCount = $ageGroups->filter(function ($ageGroup) use ($groupCode, $genderCode) {
                                        return $ageGroup->gender_code == $genderCode && is_null($ageGroup->age_group_code);
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $ageGroupCount, ['gender_code' => $genderCode, 'age_group_code' => 'null', 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $ageGroupCount = $ageGroups->filter(function ($ageGroup) use ($groupCode) {
                                    return is_null($ageGroup->age_group_code);
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $ageGroupCount, ['age_group_code' => 'null', 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                        @foreach ($genders as $genderCode => $genderName)
                            @php
                                $ageGroupCount = $ageGroups->filter(function ($ageGroup) use ($genderCode) {
                                    return $ageGroup->gender_code == $genderCode;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $ageGroupCount, ['gender_code' => $genderCode, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{ link_to_route('partners.search', $ageGroups->sum('partners_count'), ['type_code' => $partnerTypeCode]) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
