<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        table.table { margin-left: 0; margin-bottom: 10px; width: 100%; }
        table.table, table.table td, table.table th { border: 1px solid #C9C6C6; border-collapse: collapse; padding: 4px; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .pull-right { float: right; }
        .strong { font-weight: bold; }
        .page-break { page-break-after: always; }
        .hidden-print { display: none; }

        .header, .footer { font-size: 60%; width: 100%; position: relative; /*border: 1px dotted red;*/ }
        .header { top: 0px; }
        .footer { bottom: 0px; }
        .pagenum:before { content: counter(page); }
    </style>
    @yield('style')
</head>
<body>
    @yield('content')
</body>
</html>
