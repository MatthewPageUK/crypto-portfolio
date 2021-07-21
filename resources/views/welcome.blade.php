<x-guest-layout title=" - {{ __('Welcome') }}">
    <x-auth-card>
        <x-slot name="logo">
            <x-application-logo class="w-20 h-20" alt="{{ config('app.title') }}"/>
        </x-slot>

        <h1 class="text-2xl text-center my-4">{{ config('app.title') }}</h1>

        <p class="text-center">{{ config('app.description') }}</p>
        
        <p class="flex items-center mt-8 text-center">

            @if (Route::has('register'))
                <x-button-link href="{{ route('register') }}" title="{{ __('Register to start tracking your crypto') }}">
                    {{ __('Register') }}
                </x-button-link>
            @endif      

            <x-button-link href="{{ route('login') }}" title="{{ __('Log in to access your portfolio') }}">
                {{ __('Log in') }}
            </x-button-link>
        </p>

        <div class="flex justify-center mt-16 text-gray-400 text-sm sm:items-center sm:justify-between">
            <a href="http://mjp.co" 
                class="flex-grow underline hover:text-red-800" 
                target="_blank" 
                title="{{ __('PHP coding by Matt') }}"
            >
                {{ __('By Matthew Page') }}
            </a>

            <p class="text-center text-xs">v{{ config('app.version') }}</p>
            
            <a href="https://github.com/MatthewPageUK/crypto-portfolio" 
                class="flex-grow underline text-right hover:text-red-800" 
                target="_blank" 
                title="{{ __('Source code and documentation on GitHub') }}"
            >
                {{ __('Github Repository') }}
            </a>
        </div>

    </x-auth-card>
</x-guest-layout>
