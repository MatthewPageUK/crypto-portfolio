@props(['bot'])

@php
    $classes = '';
@endphp

{{-- Bot Info Box --}}
<div class="opacity-75 hover:opacity-100">

    {{-- Header --}}
    <div class="grid grid-cols-12 items-center bg-gray-200 shadow-lg rounded-t-xl py-2 px-4 text-xl">
        <div class="col-span-9">
            <strong>{{ $bot->name }}</strong> is {{ $bot->direction === 'up' ? 'Bullish' : 'Bearish' }} on
            <x-tokens.link :token="$bot->token" class="hover:text-yellow-400">
                {{ $bot->token->name }}
            </x-tokens.link>
        </div>
        <div class="col-span-3 text-right text-sm">
            Status : {{ Str::title($bot->status) }} / {{ $bot->isRunning() ? 'Running' : 'Stopped' }}
        </div>
    </div>
    <div class="grid grid-cols-12 bg-white shadow-lg p-8 bg-no-repeat bg-right-bottom"
        style="background-image: url('http://robohash.org/{{ $bot->name }}?size=200x200'); "
    >

        {{-- Info table --}}
        <div class="col-span-4 "
        >

            <table class="w-full">

                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Born</th>
                    <td class="text-left py-1">{{ $bot->created_at }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Age</th>
                    <td class="text-left py-1">{{ $bot->created_at->diffForHumans(null, true) }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Last</th>
                    <td class="text-left py-1">{{ $bot->history->last()?->created_at->diffForHumans(); }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Target</th>
                    <td class="text-left py-1">{{ $bot->profit; }}%</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Stop Loss</th>
                    <td class="text-left py-1">{{ $bot->loss; }}%</td>
                </tr>
            </table>

            <div class="mt-4 border rounded-lg">

            </div>

            {{-- <div class="mt-4 border rounded-lg bg-green-100 py-2 px-4 text-sm">
                This bot has acheived guaranteed profit of £23.45 (12.5%)
            </div> --}}

            {{-- <div class="mt-4 border rounded-lg bg-green-100 py-2 px-4 text-sm">
                You can sell now for profit of £143.45 (8.5%) [SELL]
            </div> --}}

        </div>

        {{-- Graph --}}
        <div class="col-span-8 ml-8">

            <canvas id="myChart{{ $bot->id }}" width="400" height="400"></canvas>
            <script>
            const ctx{{ $bot->id }} = document.getElementById('myChart{{ $bot->id }}').getContext('2d');
            const myChart{{ $bot->id }} = new Chart(ctx{{ $bot->id }}, {
                type: 'line',
                data: {
                    labels: [{{ $bot->history->implode('id', ', '); }}],
                    datasets: [{
                            label: 'Price',
                            data: [{{ $bot->history->implode('price', ', '); }}],

                        } , {
                            label: 'Target',
                            data: [{{ $bot->history->implode('target_price', ', '); }}],
                            borderColor: 'rgb(0, 162, 0)'

                        }, {
                            label: 'Stop Loss',
                            data: [{{ $bot->history->implode('stop_loss', ', '); }}],
                            borderColor: 'rgb(162, 0, 0)'

                        },
                    ]
                },
                options: {
                    aspectRatio: 2,
                    scales: {
                        y: {
                            suggestedMin: 0.08,
                            suggestedMax: 0.14,
                        }
                    }
                },
            });



            </script>

        </div>
    </div>
    {{-- Footer --}}
    <div class="bg-gray-200 shadow-lg rounded-b-xl py-2 px-4 text-sm">
        <table class="w-full">
            <tr>
                <th class="text-center py-1 w-1/4">Entry price</th>
                <th class="text-center py-1 w-1/4">Quantity</th>
                <th class="text-center py-1 w-1/4">Exposure</th>
                <th class="text-center py-1 w-1/4">At Risk</th>
            </tr>
            <tr>
                <td class="text-center py-1 hover:bg-gray-100">£{{ number_format($bot->price, 2) }}</td>
                <td class="text-center py-1 hover:bg-gray-100">{{ number_format($bot->quantity) }}</td>
                <td class="text-center py-1 hover:bg-red-100">£{{ $bot->quantity * $bot->price }}</td>
                <td class="text-center py-1 hover:bg-red-500 hover:text-white">£{{ (( $bot->quantity * $bot->price ) / 100 ) * $bot->loss }}</td>
            </tr>
        </table>
    </div>


</div>