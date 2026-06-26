<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{ Html::style(Vite::asset('resources/js/app.css')) }}
    {{ Html::style(url('css/plugins/select2.min.css')) }}
    {!! Html::style(url('css/plugins/select2-bootstrap.min.css')) !!}
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts._top_navigation')

        <main class="py-4">
            <div class="container-xxl">@yield('content')</div>
        </main>
    </div>
    {{ Html::script(Vite::asset('resources/js/app.js')) }}
    @include('layouts._noty')
    {{ Html::script(url('js/plugins/select2.min.js')) }}
    <script>
    (function() {
        $('.select2').select2({theme: "bootstrap"});
    })();
    </script>
    @stack('scripts')
</body>
</html>
