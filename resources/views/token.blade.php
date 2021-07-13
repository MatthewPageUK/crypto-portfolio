<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                {{ __($token->symbol . ' - ' . $token->name) }}
            </h2>
            <div class="flex flex-shrink">
                <a href="{{ route('buy', $token->id) }}" title="Buy more {{ $token->symbol }}" class="flex flex-shrink items-center text-green-600 hover:text-green-400">
                    <span class="flex-grow mr-1">Buy</span> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-4 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </a>
                @if( $token->balance > 0 )
                    <a href="{{ route('sell', $token->id) }}" title="Sell some {{ $token->symbol }}" class="flex flex-shrink items-center text-red-600 hover:text-red-400">
                        <span class="flex-grow mr-1">Sell</span> 
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                @endif
            </div>
    </x-slot>

    <div class="flex items-centered">
        <div class="flex-grow p-6 m-5 bg-white shadow-lg rounded-lg">
            <p class="text-2xl text-center">{{ $token->balance }} <span class="text-sm">{{ $token->symbol }}</span></p>
        </div>
    </div>

    <!-- Transactions table (https://tailwindcomponents.com/components/tables) -->
    <div class="overflow-x-auto">
        <div class="min-w-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
            <div class="w-full lg:w-5/6">
                <div class="bg-white shadow-md rounded my-4">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-left">Quantity</th>
                                <th class="py-3 px-6 text-right">Price</th>
                                <th class="py-3 px-6 text-right">Total</th>
                                <th class="py-3 px-6 text-center">Type</th>
                                <th class="py-3 px-6 text-center"> </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 text-sm font-light">
                            @foreach ($token->transactions as $transaction)
                                <tr class="border-b border-gray-200 hover:bg-{{ ($transaction->type==="sell")?'red':'green' }}-300 bg-{{ ($transaction->type==="sell")?'red':'green' }}-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        {{ $transaction->time->format('j F \'y') }}
                                        <span class="text-xs">{{ $transaction->time->format('h:i:s A') }}</span>
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        {{ $transaction->quantity }} <span class="text-xs">{{ $token->symbol }}</span>
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        &pound;{{ number_format($transaction->price, 4) }}
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        &pound;{{ number_format($transaction->total(), 4) }}
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        {{ ucwords($transaction->type) }}
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        <div class="flex item-center justify-center">
                                            <div class="w-4 mr-2 transform hover:scale-110">
                                                <a href="{{ route('deletetransaction', ['cryptoTransaction' => $transaction->id]) }}" onclick="return confirm('Delete this transaction?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>








