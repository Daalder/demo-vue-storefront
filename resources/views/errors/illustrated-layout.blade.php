<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="antialiased font-sans">
<div class="md:flex min-h-screen">
    <div class="w-full md:w-1/2 bg-white flex items-center justify-center">
        <div class="max-w-sm m-8">
            <img src="/images/logo-blue2x.png" srcset="/images/logo-blue2x.png 2x" class="h-16 w-auto"
                 alt="$Daalder Shop UI">

            <div class="text-black text-6xl md:text-huge font-black">
                @yield('code', __('Oh no'))
            </div>

            <div class="w-16 h-1 bg-blue-500 my-3 md:my-6"></div>

            <p class="text-grey-darker text-2xl md:text-3xl font-light mb-8 leading-normal">
                @yield('message')
            </p>

            <a href="{{ app('router')->has('home') ? route('home') : url('/') }}">
                <button
                    class="bg-transparent text-grey-darkest font-bold uppercase tracking-wide py-3 px-6 border-2 border-grey-light hover:border-grey rounded-lg">
                    {{ __('Go to Products') }}
                </button>
            </a>
        </div>
    </div>

    <div class="relative pb-full md:flex md:pb-0 md:min-h-screen w-full md:w-1/2 overflow-hidden">
        @hasSection('image')
            @yield('image')
        @else
            <div class="w-full h-full bg-cover"
                 style="background-image: url('https://source.unsplash.com/featured/?landscape');"></div>
        @endif
    </div>
</div>
</body>
</html>
