<x-app-layout title=" - {{ __('Dashboard') }}">
    <x-slot name="header">
        <div class="flex items-center">

            {{-- Title --}}
            <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            {{-- Add Token link --}}
            <div class="flex-grow">
                <a href="{{ route('token.create') }}" title="{{ __('Add a new token') }}" class="flex items-center text-right hover:text-green-500">
                    <span class="flex-grow">{{ __('Add Token') }}</span> 
                    <x-icons.plus class="ml-1 w-6" />
                </a>
            </div>

        </div>
    </x-slot>
 
    <div class="overflow-x-auto">
        <div class="min-w-screen bg-gray-100 flex flex-wrap items-center justify-center overflow-hidden">

            <!-- Tokens -->
            @foreach ($tokens as $token)
                <x-tokens.infobox :token="$token" />
            @endforeach
            
        </div>
    </div>

</x-app-layout>
