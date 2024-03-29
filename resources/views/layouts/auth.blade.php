<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/apple-touch-icon.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/favicon-16x16.png')}}">
        <link rel="manifest" href="{{asset('assets/site.webmanifest')}}">
        <link rel="mask-icon" href="{{asset('assets/safari-pinned-tab.svg')}}" color="#ffffff">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">

        {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}
        {!! Twitter::generate() !!}
        {!! JsonLd::generate() !!}
        <link rel="icon" href="{{asset('assets/logo.jpg')}}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

        @include('meta-pixel::script')
        @filamentStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

    <body class="font-sans antialiased">

        <div class="min-h-screen">

            <div >
                <main>
                    <div class="fixed top-0 z-20 w-screen" >
                       <livewire:layout.authNavigation />
                    </div>
                    <div class="bg-white">
                        <main>
                            {{ $slot }}
                        </main>
                    </div>
                    <div class="fixed bottom-0 z-20 w-screen">
                        <livewire:layout.authFooter>
                    </div>
                </main>
            </div>
        </div>
        <!-- Alpine Plugins -->
    </body>

</html>
