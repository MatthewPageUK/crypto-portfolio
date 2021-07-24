<x-app-layout title=" - {{ __($transaction->cryptoToken->symbol . ' - Transaction ID ' . $transaction->id) }}">
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex flex-grow">
                <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                    {{ __($transaction->cryptoToken->symbol . ' - Transaction ID ' . $transaction->id) }} 

                    <a href="{{ route('transaction.edit', $transaction->id) }}" title="Edit this transaction" class="w-4 inline-block ml-2 text-gray-500 transform hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                    <a href="{{ route('transaction.delete', $transaction->id) }}" title="Delete this transaction" class="w-4 inline-block text-gray-500 transform hover:scale-110" onclick="return confirm('Delete this transactions?')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </h2>   
            </div>
    </x-slot>

    {{-- <div class="min-w-screen flex items-center justify-center">
        <div class="flex items-center w-full lg:w-5/6">
            <div class="flex-grow p-6 m-5 bg-white shadow-lg rounded-lg">
                <p class="text-2xl text-center">
                    <span class="text-sm block">{{ __('Still Hodling') }}</span> 
                    <x-quantity :quantity="$transaction->cryptoToken->balance()" />
                    <span class="text-xs">30%</span>
                </p>
            </div>
            <div class="flex-grow p-6 m-5 bg-white shadow-lg rounded-lg">
                <p class="text-2xl text-center"><span class="text-sm block">{{ __('Quantity Sold') }}</span> <x-currency :amount="$transaction->cryptoToken->averageBuyPrice()" /></p>
            </div>
            <div class="flex-grow p-6 m-5 bg-white shadow-lg rounded-lg">
                <p class="text-2xl text-center"><span class="text-sm block">{{ __('Profit / Loss') }}</span> <x-currency :amount="$transaction->cryptoToken->averageHodlBuyPrice()" /></p>
            </div>
            <div class="flex-grow p-6 m-5 bg-white shadow-lg rounded-lg">
                <p class="text-2xl text-center"><span class="text-sm block">{{ __('Avg. Sell price') }}</span> <x-currency :amount="$transaction->cryptoToken->averageSellPrice()" /></p>
            </div>
        </div>
    </div> --}}

    <!-- Transaction table  -->
    <div class="overflow-x-auto">
        <div class="min-w-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
            <div class="w-full lg:w-5/6">
                <div class="bg-white shadow-md rounded my-4">
                    <table class="min-w-max w-full table-fixed md:table-auto">
                        <tbody class="text-gray-800 text-sm font-light">
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">ID</th>
                                <td class="w-full py-3 px-6 text-left">{{ $transaction->id }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">Token</th>
                                <td class="py-3 px-6 text-left"><a href="{{ route('token.show', ['token' => $transaction->cryptoToken]) }}">
                                    {{ $transaction->cryptoToken->symbol }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>                                    
                                </a></td>
                            </tr>
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">Date</th>
                                <td class="py-3 px-6 text-left">
                                    <span class="whitespace-nowrap">{{ $transaction->time->format('j F \'y') }}</span>
                                    <span class="whitespace-nowrap text-xs">{{ $transaction->time->format('h:i:s A') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">Type</th>
                                <td class="py-3 px-6 text-left">{{ ucwords($transaction->type); }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">Price</th>
                                <td class="py-3 px-6 text-left">{{ $transaction->price->humanReadable() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">Quantity</th>
                                <td class="py-3 px-6 text-left">{{ $transaction->quantity->humanReadable() }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-gray-500">Total</th>
                                <td class="py-3 px-6 text-left">{{ $transaction->total()->humanReadable() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>








