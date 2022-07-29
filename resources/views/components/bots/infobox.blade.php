@props(['bot'])

@php
    $classes = 'col-span-full sm:col-span-1 bg-white shadow-lg rounded-xl p-8 opacity-75 hover:opacity-100';
@endphp

{{-- Bot Info Box --}}
<div {{ $attributes->merge(['class' => $classes]) }} >

    <div class="bg-no-repeat bg-right-top"
        style="background-image: url('http://robohash.org/{{ $bot->name }}?size=200x200'); background-position: right -25px top -20px"
    >

        <div class="" style="padding-right: 150px">

            {{-- Quantity --}}
            {{-- <p class="p-2 rounded-lg font-black text-white">
                <x-quantity :quantity="$bot->quantity" />
            </p> --}}

            <table class="w-full">
                <tr class="border-b border-gray-500">
                    <th class="text-left py-2">Name</th>
                    <td class="text-left py-2">{{ $bot->name }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1 pt-2">Trading</th>
                    <td class="text-left py-1 pt-2">
                        <x-tokens.link :token="$bot->token" class="hover:text-yellow-400">
                            {{ $bot->token->name }}
                        </x-tokens.link>
                    </td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Type</th>
                    <td class="text-left py-1">{{ $bot->direction }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Born</th>
                    <td class="text-left py-1">{{ $bot->created_at }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Age</th>
                    <td class="text-left py-1">{{ $bot->created_at->diffForHumans() }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Status</th>
                    <td class="text-left py-1">{{ $bot->status }}</td>
                </tr>
                <tr class="hover:bg-gray-100">
                    <th class="text-left py-1">Running</th>
                    <td class="text-left py-1">{{ $bot->isRunning() ? 'Yes' : 'No' }}</td>
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
        </div>

        <div class="mt-4 border rounded-lg">
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

        {{-- <div class="mt-4 border rounded-lg bg-green-100 py-2 px-4 text-sm">
            This bot has acheived guaranteed profit of £23.45 (12.5%)
        </div> --}}

        {{-- <div class="mt-4 border rounded-lg bg-green-100 py-2 px-4 text-sm">
            You can sell now for profit of £143.45 (8.5%) [SELL]
        </div> --}}


    </div>
    <div class="mt-8">

        <canvas id="myChart" width="400" height="400"></canvas>
        <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'],
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>
    </div>
</div>