<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.title') }}</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="antialiased">
        <x-guest-layout>
            <x-auth-card>
                <x-slot name="logo">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" alt="{{ config('app.title') }}"/>
                </x-slot>

                <h1 class="text-2xl text-center mb-4 mt-4">{{ config('app.title') }}</h1>
                <p class="text-center">{{ config('app.description') }}</p>
                <p class="flex items-center mt-8 text-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="flex-grow m-1 p-3 uppercase bg-yellow-500 rounded-lg hover:bg-yellow-400 hover:font-black hover:text-red-800"
                            title="Register to start tracking your crypto">Register</a> 
                    @endif                                    
                    <a href="{{ route('login') }}" class="flex-grow m-1 p-3 uppercase bg-yellow-500 rounded-lg hover:bg-yellow-400 hover:font-black hover:text-red-800"
                        title="Log in to access your portfolio">Log in</a>
                </p>

                <div class="flex justify-center mt-16 text-sm sm:items-center sm:justify-between">
                    <a href="http://mjp.co" class="flex-grow underline hover:text-red-800" target="_blank" title="PHP coding by Matt">
                        By Matthew Page
                    </a>
                    <a href="https://github.com/MatthewPageUK/crypto-portfolio" class="flex-grow text-right underline hover:text-red-800" target="_blank" title="Source code and documentation on GitHub">
                        GitHub Repository
                    </a>
                </div>

            </x-auth-card>
        </x-guest-layout>
    </body>
</html>

