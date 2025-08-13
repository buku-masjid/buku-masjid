<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('transaction.print_spending_request') }}</title>
    <style>
        html {
            font-family: "Times New Roman";
        }
        table.receipt-table {
            /*border: 1px solid #aaa;*/
            border-collapse: collapse;
            font-size:16px;
        }
        table.receipt-table td {
            padding: 5px;
        }
        @page {
            size: auto;
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
            margin-header: 20px;
            margin-footer: 20px;
        }
    </style>
</head>
<body>
    <table style="border-collapse: collapse;">
        <tbody>
            <tr>
                <td style="width:140px;">
                    @if (Setting::get('masjid_logo_path'))
                        <img src="{{ Storage::url(Setting::get('masjid_logo_path'))}}" style="width: 75px">
                    @endif
                </td>
                <td style="width:330px">
                    <div style="width:280px">
                        <h4 style="margin:0px; border-bottom: 3px; font-size: 21.5px">{{ Setting::get('masjid_name') }}</h4>
                        @if (Setting::get('masjid_address'))
                        <hr style="margin: 2px 0">
                        <div style="font-size:11px">
                            {{ Setting::get('masjid_address') }}
                        </div>
                        @endif
                    </div>
                </td>
                <td style="width:280px; text-align: center;">
                    <h3 style="margin: 3px 0; font-size: 20px">{{ __('transaction.spending_request') }}</h3>
                    <div style="font-size: 10px"><br>{{ __('app.printed_at') }}: {{ now()->isoFormat('DD MMM YYYY HH:mm:ss') }}</div>
                </td>
            </tr>
            <tr>
                <td >{{ __('transaction.paid_to') }} : </td>
                <td colspan="2" style="border-bottom: 1px solid #ccc;height: 35px;">
                    {{ optional($transaction->partner)->name }}
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td>{{ __('transaction.words_amount') }} : </td>
                <td colspan="2" style="border-bottom: 1px solid #ccc;height: 35px">
                    {{ ucwords(Illuminate\Support\Number::spell($transaction->amount)) }} {{ config('money.currency_text') }}
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td>{{ __('app.description') }} :</td>
                <td colspan="2" style="border-bottom: 1px solid #ccc;height: 35px">
                    {!! $transaction->date_alert !!} {!! nl2br(htmlentities($transaction->description)) !!}
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td>{{ __('transaction.cash_amount') }}: </td>
                <td colspan="2" style="border-bottom: 1px solid #ccc;height: 35px">
                    {{ config('money.currency_code') }} {{ format_number($transaction->amount) }}
                </td>
            </tr>
        </tbody>
    </table>
    <table {{-- border="1" --}} class="text-center" style="border-collapse: collapse; width: 100%;">
        <tbody>
            <tr>
                <td style="width:33%;height: 40px">&nbsp;</td>
                <td style="width:33%">&nbsp;</td>
                <td style="width:33%">
                    @if (Setting::get('masjid_city_name'))
                        {{ Setting::get('masjid_city_name') }}, {{ Carbon\Carbon::parse($transaction->date)->isoFormat('D MMMM Y') }}
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
</body>
</html>
