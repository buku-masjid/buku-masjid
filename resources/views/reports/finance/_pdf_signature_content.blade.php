<table {{-- border="1" --}} class="text-center" style="border-collapse: collapse; width: 100%;page-break-inside: avoid;">
    <tbody>
        <tr>
            <td style="width:33%;height: 40px">&nbsp;</td>
            <td style="width:33%">&nbsp;</td>
            <td style="width:33%">
                @if (Setting::get('masjid_city_name'))
                    {{ Setting::get('masjid_city_name') }}, {{ now()->isoFormat('D MMMM Y') }}
                @else
                    &nbsp;
                @endif
            </td>
        </tr>
        <tr>
            <td>{{ Setting::for(auth()->activeBook())->get('acknowledgment_text_left') }}</td>
            <td>{{ Setting::for(auth()->activeBook())->get('acknowledgment_text_mid') }}</td>
            <td>{{ Setting::for(auth()->activeBook())->get('acknowledgment_text_right') }}</td>
        </tr>
        <tr>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_position_left') }}</td>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_position_mid') }}</td>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_position_right') }}</td>
        </tr>
        <tr>
            <td style="height: 70px">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_name_left') }}</td>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_name_mid') }}</td>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_name_right') }}</td>
        </tr>
    </tbody>
</table>
