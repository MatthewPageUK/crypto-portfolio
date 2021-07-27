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
 
    <div class="overflow-x-auto mt-2">
        <div class="min-w-screen bg-gray-100 flex flex-wrap items-center justify-center overflow-hidden border-b-2 border-gray-300 mx-6">

            <!-- Tokens with a balance -->
            @foreach ($tokens as $token)
                @if ( $token->balance()->gt(0) )
                    <x-tokens.infobox :token="$token" class="flex-grow" />
                @endif
            @endforeach
            
        </div>
        <div class="min-w-screen bg-gray-100 flex flex-wrap items-center justify-center overflow-hidden mb-16 mx-6">

            <!-- Tokens with zero balance -->
            @foreach ($tokens as $token)
                @if ( $token->balance()->lte(0) )
                    <x-tokens.infobox :token="$token" class="flex-grow" />
                @endif
            @endforeach
            
        </div>        
    </div>

</x-app-layout>
