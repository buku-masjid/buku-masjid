<table {{-- border="1" --}} class="text-center" style="border-collapse: collapse; width: 100%;">
    <tbody>
        <tr>
            <td style="width:33%">&nbsp;</td>
            <td style="width:33%">&nbsp;</td>
            <td style="width:33%">{{ Setting::get('masjid_city_name') }}, {{ now()->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td style="height: 40px">&nbsp;</td>
            <td>{{ __('report.acknowledgment') }},</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_position_left') }}</td>
            <td>&nbsp;</td>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_position_right') }}</td>
        </tr>
        <tr>
            <td style="height: 70px">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_name_left') }}</td>
            <td>&nbsp;</td>
            <td>{{ Setting::for(auth()->activeBook())->get('sign_name_right') }}</td>
        </tr>
    </tbody>
</table>