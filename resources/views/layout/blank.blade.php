<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preload" href="https://fonts.gstatic.com/s/materialicons/v55/flUhRq6tzZclQEJ-Vdg-IuiaDsNcIhQ8tQ.woff2"
          as="font" crossorigin>
    <link rel="preload" href="/fonts/RawsonPro-Light.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/RawsonPro-Bold.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/RawsonPro-Medium.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/RawsonPro-SemiBold.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/RawsonPro-ExtraBold.woff2" as="font" crossorigin>
    <link rel="preload" href="/images/logo-blue.png" as="image">

    <title>$Daalder Shop UI</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    @if(app()->environment() === 'production')
        @include('partials/ga')
    @endif
</head>
<body>
<div id="app" store="{{ json_encode($store) }}" user="{{ json_encode($user) }}" class="h-screen flex bg-white">
    <!-- Off-canvas menu for mobile -->
    <mobile-sidebar v-cloak>
        @yield('sidebar')
    </mobile-sidebar>


    {{--    <!-- Static sidebar for desktop -->--}}
    {{--    <div class="hidden md:flex md:flex-shrink-0">--}}
    {{--        <div class="flex flex-col w-64 border-r border-gray-200 bg-white">--}}
    {{--            @yield('sidebar')--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="flex flex-col w-0 flex-1">
        @hasSection('header')
            @yield('header')
        @else
            @include('partials/header-blank')
        @endif
        <div class="flex">
            <!-- Static sidebar for desktop -->
            <div class="hidden md:flex md:flex-shrink-0 pt-4 mt-1 bg-gray-100">
                @yield('sidebar')
            </div>
            <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 focus:outline-none md:py-6" tabindex="0">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('modals')
</div>
<script src="/js/app.js" rel="script"></script>
</body>
</html>
