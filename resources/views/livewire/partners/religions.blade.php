<div wire:init="getReligions">
    @if ($isLoading)
        <div class="loading-state text-center w-100">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <div class="row align-items-end">
            <div class="col-12 col-sm-8">
                <div class="h3">{{ __('partner.religion') }}</div>
            </div>
            <div class="col-12 col-sm-4 text-right mb-2"></div>
        </div>

        <div class="card table-responsive-sm">
            <table class="table-sm table-striped table-bordered small">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.religion') }}</th>
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
                    @foreach (__('partner.religions') as $religionId => $religionName)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $religionName }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $religionCount = $religions->filter(function ($religion) use ($religionId, $genderCode) {
                                        return $religion->gender_code == $genderCode && $religion->religion_id == $religionId;
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $religionCount, ['gender_code' => $genderCode, 'religion_id' => $religionId, 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $religionCount = $religions->filter(function ($religion) use ($religionId) {
                                    return $religion->religion_id == $religionId;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $religionCount, ['religion_id' => $religionId, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $unknownMaritalStatusCount = $religions->filter(function ($religion) use ($religionId) {
                            return is_null($religion->religion_id);
                        })->sum('partners_count');
                    @endphp
                    @if ($unknownMaritalStatusCount)
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ __('app.unknown') }}</td>
                            @foreach ($genders as $genderCode => $genderName)
                                @php
                                    $religionCount = $religions->filter(function ($religion) use ($religionId, $genderCode) {
                                        return $religion->gender_code == $genderCode && is_null($religion->religion_id);
                                    })->sum('partners_count');
                                @endphp
                                <td class="text-center">
                                    {{ link_to_route('partners.search', $religionCount, ['gender_code' => $genderCode, 'religion_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                                </td>
                            @endforeach
                            @php
                                $religionCount = $religions->filter(function ($religion) use ($religionId) {
                                    return is_null($religion->religion_id);
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $religionCount, ['religion_id' => 'null', 'type_code' => $partnerTypeCode]) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="strong">
                        <td colspan="2" class="text-right">{{ __('app.total') }}</td>
                        @foreach ($genders as $genderCode => $genderName)
                            @php
                                $religionCount = $religions->filter(function ($religion) use ($genderCode) {
                                    return $religion->gender_code == $genderCode;
                                })->sum('partners_count');
                            @endphp
                            <td class="text-center">
                                {{ link_to_route('partners.search', $religionCount, ['gender_code' => $genderCode, 'type_code' => $partnerTypeCode]) }}
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{ link_to_route('partners.search', $religions->sum('partners_count'), ['type_code' => $partnerTypeCode]) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
