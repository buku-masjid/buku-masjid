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
    {{ Html::style(Vite::asset('resources/sass/app.scss')) }}
    {{ Html::style(url('css/plugins/select2.min.css')) }}
    {!! Html::style(url('css/plugins/select2-bootstrap.min.css')) !!}
    @livewireStyles
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts._top_navigation')

        <main class="py-4">
            <div class="container-xxl">@yield('content')</div>
        </main>

        {{-- Space for the fixed-bottom mobile navigation (56px tall, visible below 576px only). --}}
        <div id="mobile-nav-spacer" class="d-block d-sm-none" style="height: 3.5rem"></div>
    </div>
    {{ Html::script(Vite::asset('resources/js/app.js')) }}
    @include('layouts._noty')
    {{ Html::script(url('js/plugins/select2.min.js')) }}
    <script>
    (function() {
        $('.select2').select2({theme: "bootstrap"});
    })();
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
