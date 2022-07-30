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



{{-- Bot Info Box --}}
<div class="opacity-90 hover:opacity-100">

    {{-- Header --}}
    <div class="grid grid-cols-12 items-center bg-gray-200 shadow-lg rounded-t-xl py-2 px-4">
        <div class="col-span-4 text-xl font-bold">
            {{ $bot->name }}'s memories.
        </div>
        <div class="col-span-4 text-center text-sm">
            Active {{ $bot->history->last()?->created_at->diffForHumans(); }}
        </div>
        <div class="col-span-4 text-right text-sm">
            Status : {{ Str::title($bot->status) }} / {{ $bot->isRunning() ? 'Running' : 'Stopped' }}
        </div>
    </div>





    {{-- Memories --}}
    <div class="bg-white p-6 text-sm">
        <table class="w-full">
            <tr>
                <th class="text-left">Date</th>
                <th class="text-left">Target price</th>
                <th class="text-left">Stop loss</th>
                <th class="text-left">Price</th>
                <th class="w-1/2 text-right">Note</th>
            </tr>

        @foreach($bot->history as $history)

            {{-- @if($history->note !== 'NOP') --}}

                <tr>
                    <td>{{ $history->created_at }}</td>
                    <td>{{ number_format($history->target_price, 4) }}</td>
                    <td>{{ number_format($history->stop_loss, 4) }}</td>
                    <td>{{ number_format($history->price, 4) }}</td>
                    <td class="text-right">{{ $history->note }}</td>
                </tr>

            {{-- @endif --}}

        @endforeach

        </table>

    </div>


    {{-- Footer --}}
     <div class="grid grid-cols-12 items-center bg-gray-200 shadow-lg rounded-b-xl py-2 px-4 text-sm">

        <div class="col-span-4 font-bold">

        </div>

        <div class="col-span-4">

        </div>

        <div class="col-span-4 text-right">
            <x-button-link href="{{ route('bot.show', ['bot' => $bot]) }}">Back</x-button-link>
        </div>

    </div>

</div>





































    </div>

</x-app-layout>
