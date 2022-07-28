<x-app-layout title=" - {{ __('Trading Bots') }}">
    <x-slot name="header">
        <div class="flex items-center">

            {{-- Title --}}
            <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Trading Bots') }}
            </h2>

            {{-- Add Bot link --}}
            <div class="flex-grow">
                <a href="{{ route('bot.create') }}" title="{{ __('Create a new bot') }}" class="flex items-center text-right hover:text-green-500">
                    <span class="flex-grow">{{ __('Create Bot') }}</span>
                    <x-icons.plus class="ml-1 w-6" />
                </a>
            </div>

        </div>
    </x-slot>

    <div class="overflow-x-auto p-16">

        <div class="grid grid-cols-3 gap-6">

            @foreach ($bots as $bot)

                    <x-bots.infobox :bot="$bot" />

            @endforeach

        </div>
    </div>

</x-app-layout>
