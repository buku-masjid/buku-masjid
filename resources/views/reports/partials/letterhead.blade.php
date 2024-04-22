@if ($showLetterhead)
    <table border="1" style="width:100%;border-collapse: collapse;">
        <tbody>
            <tr>
                @if (Setting::get('masjid_logo_path'))
                    <td style="border: 0px; border-bottom: 1px solid #000; width: 120px" class="text-center">
                        <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 75px">
                    </td>
                    <td style="border: 0px; border-bottom: 1px solid #000;height: 78px; padding-right: 10%" class="text-center">
                @else
                    <td style="border: 0px; border-bottom: 1px solid #000;height: 78px" class="text-center">
                @endif
                    <h2 class="uppercase">
                        {{ Setting::for(auth()->activeBook())->get('management_title', __('report.management')) }}
                        <br/>
                        {{ Setting::get('masjid_name', config('masjid.name')) }}
                    </h2>
                    <div>{{ __('masjid_profile.address') }}: {!! nl2br(htmlentities(Setting::get('masjid_address'))) !!}</div>
                </td>
            </tr>
        </tbody>
    </table>
@endif
