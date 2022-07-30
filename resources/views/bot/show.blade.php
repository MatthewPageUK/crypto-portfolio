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
        <div class="col-span-4 text-xl">
            <strong>{{ $bot->name }}</strong> is {{ $bot->getAnimal() }}ish on
            <x-tokens.link :token="$bot->token" class="hover:text-yellow-400">
                {{ $bot->token->name }}
            </x-tokens.link>
        </div>
        <div class="col-span-4 text-center text-sm">
            Active {{ $bot->history->last()?->created_at->diffForHumans(); }}
        </div>
        <div class="col-span-4 text-right text-sm">
            Status : {{ Str::title($bot->status) }} / {{ $bot->isRunning() ? 'Running' : 'Stopped' }}
        </div>
    </div>













    {{-- Graph --}}

    <div class="bg-white p-6">

        <canvas id="myChart{{ $bot->id }}" width="400" height="400"></canvas>
        <script>
            @php
                $cnt = $bot->history()->orderBy('created_at', 'desc')->limit(5000)->count();
                $buyPrices = array_fill(0, $cnt, $bot->price);

                $labels = [];
                foreach ($bot->history()->orderBy('created_at', 'desc')->limit(5000)->get()->sortBy('created_at') as $bh) {
                    $labels[] = "'".$bh->created_at->format('G:i')."'";
                }
            @endphp
            const ctx{{ $bot->id }} = document.getElementById('myChart{{ $bot->id }}').getContext('2d');
            const myChart{{ $bot->id }} = new Chart(ctx{{ $bot->id }}, {
                type: 'line',
                data: {
                    labels: [{!! implode(', ', $labels); !!}],
                    datasets: [{
                            label: 'Price',
                            data: [{{ $bot->history()->orderBy('created_at', 'desc')->limit(5000)->get()->sortBy('created_at')->implode('price', ', '); }}],
                            borderColor: 'rgb(100, 100, 162)',
                            borderWidth: 2,
                            pointRadius: 0,
                        } , {
                            label: 'Target',
                            data: [{{ $bot->history()->orderBy('created_at', 'desc')->limit(5000)->get()->sortBy('created_at')->implode('target_price', ', '); }}],
                            borderColor: 'rgb(0, 162, 0)',
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 2,

                        }, {
                            label: 'Stop Loss',
                            data: [{{ $bot->history()->orderBy('created_at', 'desc')->limit(5000)->get()->sortBy('created_at')->implode('stop_loss', ', '); }}],
                            borderColor: 'rgb(162, 0, 0)',
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 2,

                        }, {
                            label: 'Buy price',
                            data: [{{ implode(', ', $buyPrices) }}],
                            borderColor: 'rgb(162, 162, 162)',
                            borderWidth: 1,
                            pointRadius: 0,
                            pointHoverRadius: 2,

                        },
                    ]
                },
                options: {
                    aspectRatio: 2,
                    scales: {
                        y: {
                            suggestedMin: {{ $bot->price - ( ( $bot->price / 100 ) * ( $bot->loss + 10 ) ) }},
                            suggestedMax: {{ $bot->price + ( ( $bot->price / 100 ) * ( $bot->profit + 10 ) ) }},
                        }
                    }
                },
            });
        </script>
    </div>



    {{-- Panel --}}
    <div class="grid grid-cols-12 bg-white shadow-lg p-4 bg-no-repeat border-b"
        style="background-image: url('https://robohash.org/{{ $bot->name }}{{ $bot->id }}?size=200x200'); background-position: right -20px bottom"
    >

        {{-- Info table --}}
        <div class="col-span-10">

            <table class="w-full text-sm">

                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Born</th>
                    <td class="text-left py-1 text-xs" colspan="3">{{ $bot->created_at }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Age</th>
                    <td class="text-left py-1 w-1/4">{{ $bot->created_at->diffForHumans(null, true) }}</td>
                    <th class="text-left py-1">Quantity</th>
                    <td class="text-left py-1 w-1/4 hover:bg-gray-100">{{ number_format($bot->quantity) }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Target</th>
                    <td class="text-left py-1">{{ $bot->profit; }}%</td>
                    <th class="text-left py-1">Exposure</th>
                    <td class="text-left py-1 hover:bg-red-100">£{{ number_format($bot->getExposure(), 2) }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Stop Loss</th>
                    <td class="text-left py-1">{{ $bot->loss; }}%</td>
                    <th class="text-left py-1">Risk</th>
                    <td class="text-left py-1 hover:bg-red-500 hover:text-white">£{{ number_format($bot->getRisk(), 2) }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1 pr-1">Entry price</th>
                    <td class="text-left py-1 hover:bg-gray-100">£{{ number_format($bot->price, 4) }}</td>
                    <th class="text-left py-1">Gain</th>
                    <td class="text-left py-1 hover:bg-green-500 hover:text-white">£{{ number_format($bot->getGain(), 2) }}</td>
                </tr>
            </table>

{{--
            <div class="mt-4 border rounded-lg bg-green-100 py-2 px-4 text-sm">
                This bot has acheived guaranteed profit of £23.45 (12.5%)
            </div> --}}

            {{-- <div class="mt-4 border rounded-lg bg-green-100 py-2 px-4 text-sm">
                You can sell now for profit of £143.45 (8.5%) [SELL]
            </div> --}}

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

            @if($history->note !== 'NOP')

                <tr>
                    <td>{{ $history->created_at }}</td>
                    <td>{{ number_format($history->target_price, 4) }}</td>
                    <td>{{ number_format($history->stop_loss, 4) }}</td>
                    <td>{{ number_format($history->price, 4) }}</td>
                    <td class="text-right">{{ $history->note }}</td>
                </tr>

            @endif

        @endforeach

        </table>
        <div class="mt-4">
            <x-button-link href="{{ route('bot.memories', ['bot' => $bot]) }}">More memories</x-button-link>
        </div>

    </div>


    {{-- Footer --}}
     <div class="grid grid-cols-12 items-center bg-gray-200 shadow-lg rounded-b-xl py-2 px-4 text-sm">

        <div class="col-span-4 font-bold">
            Current Value : £{{ number_format($bot->getCurrentValue(), 2) }}
        </div>

        <div class="col-span-4">
            <div class="bg-{{ $bot->getProfitLoss() > 0 ? 'green' : 'red' }}-500
                py-2 px-4 rounded-full text-white text-center text-xs">

                {{ $bot->getProfitLoss() > 0 ? '+' : '-' }}£{{ number_format(abs($bot->getProfitLoss()), 2) }}
            </div>

        </div>

        <div class="col-span-4 text-right">
            {{-- <x-button>Pause</x-button> --}}
            <x-button>Sell</x-button>
        </div>

    </div>

</div>





































    </div>

</x-app-layout>
