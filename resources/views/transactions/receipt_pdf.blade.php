<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('transaction.print_receipt') }}</title>
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
    <table class="receipt-table">
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
                    <h3 style="margin: 3px 0; font-size: 20px">
                        @if ($transaction->in_out)
                            {{ __('transaction.income_receipt') }}
                        @else
                            {{ __('transaction.spending_receipt') }}
                        @endif
                    </h3>
                    <div style="font-size: 10px"><br>{{ __('app.printed_at') }}: {{ now()->isoFormat('DD MMM YYYY HH:mm:ss') }}</div>
                </td>
            </tr>
            <tr>
                <td>{{ __('transaction.from') }} : </td>
                <td colspan="2" style="border-bottom: 1px solid #ccc;">
                    @if ($transaction->in_out)
                        {{ optional($transaction->partner)->name }}
                    @else
                        {{ Setting::get('masjid_name') }}
                    @endif
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
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align: center;">
                    @php
                        $cityName = Setting::get('masjid_city_name') ? Setting::get('masjid_city_name').', ' : '';
                        $dateText = Illuminate\Support\Carbon::parse($transaction->date)->isoFormat('DD MMMM YYYY');
                        $fullText = $cityName.$dateText;
                    @endphp
                    @if (strlen($fullText) > 32)
                        {{ $cityName }}<br>{{ $dateText }}
                    @else
                        {{ $cityName }}{{ $dateText }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size: 16px; font-weight: bold; text-align: center;height: 100px;vertical-align: bottom;">{{ __('transaction.cash_amount') }}</td>
                <td style="font-size: 16px; font-weight: bold; vertical-align: bottom;">
                    {{ config('money.currency_code') }} {{ format_number($transaction->amount) }}
                </td>
                <td style="text-align: center;vertical-align: bottom;">
                    @if ($transaction->in_out)
                        <strong>{{ auth()->user()->name }}</strong><br>
                        {{ Setting::get('masjid_name') }}
                    @else
                        {{ optional($transaction->partner)->name }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
