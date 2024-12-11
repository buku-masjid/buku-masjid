<div wire:init="getWorkTypes">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="row align-items-end">
            <div class="col-12 col-sm-8">
                <div class="h3">{{ __('partner.work_type') }}</div>
            </div>
            <div class="col-12 col-sm-4 text-right mb-2"></div>
        </div>

        <div class="card table-responsive-sm">
            <table class="table-sm table-striped table-bordered small">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.work_type') }}</th>
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
                    @foreach (__('partner.work_types') as $workTypeId => $workTypeName)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $workTypeName }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $workTypeCount = $workTypes->filter(function ($workType) use ($workTypeId, $genderCode) {
                                        return $workType->gender_code == $genderCode && $workType->work_type_id == $workTypeId;
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $workTypeCount, ['gender_code' => $genderCode, 'work_type_id' => $workTypeId, 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $workTypeCount = $workTypes->filter(function ($workType) use ($workTypeId) {
                                    return $workType->work_type_id == $workTypeId;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $workTypeCount, ['work_type_id' => $workTypeId, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $unknownMaritalStatusCount = $workTypes->filter(function ($workType) use ($workTypeId) {
                            return is_null($workType->work_type_id);
                        })->sum('partners_count');
                    @endphp
                    @if ($unknownMaritalStatusCount)
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ __('app.unknown') }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $workTypeCount = $workTypes->filter(function ($workType) use ($workTypeId, $genderCode) {
                                        return $workType->gender_code == $genderCode && is_null($workType->work_type_id);
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $workTypeCount, ['gender_code' => $genderCode, 'work_type_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $workTypeCount = $workTypes->filter(function ($workType) use ($workTypeId) {
                                    return is_null($workType->work_type_id);
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $workTypeCount, ['work_type_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                        @foreach ($genders as $genderCode => $genderName)
                            @php
                                $workTypeCount = $workTypes->filter(function ($workType) use ($genderCode) {
                                    return $workType->gender_code == $genderCode;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $workTypeCount, ['gender_code' => $genderCode, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{ link_to_route('partners.search', $workTypes->sum('partners_count'), ['type_code' => $partnerTypeCode]) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
