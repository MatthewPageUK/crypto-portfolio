<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.title') }}{{ $title }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Caveat&display=swap">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.2/chart.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-fixed bg-cover" style="background-image: url({{ asset('img/back.jpg') }})">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="w-full py-6 px-4 sm:px-6 lg:px-12">
                    {{ $header }}
                </div>
            </header>

            @if(session()->has('success'))
                <div class="popupmessage bg-green-500 p-2 text-center text-white font-bold">
                    {{ session()->get('success') }}
                </div>
                <script>
                    setTimeout( function() { document.querySelector('.popupmessage').style.display = 'none' } , 3000);
                </script>
            @endif

            @if(session()->has('failure'))
                <div class="popupmessage bg-red-500 p-2 text-center text-white font-bold">
                    {{ session()->get('failure') }}
                </div>
                <script>
                    setTimeout( function() { document.querySelector('.popupmessage').style.display = 'none' } , 3000);
                </script>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
