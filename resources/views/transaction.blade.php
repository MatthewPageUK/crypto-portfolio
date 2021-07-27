<x-app-layout title=" - {{ $transaction->humanReadable() }}">
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex flex-grow">
                <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                    {{ $transaction->humanReadable() }}
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

    {{-- Info Boxes --}}
    <div class="min-w-screen flex items-center justify-center my-8">
        <div class="flex items-top w-full lg:w-5/6">
            <div class="flex-1">
                <x-transaction-card :transaction="$transaction" />
            </div>
            <div class="flex-1">
                @php
                    $rel = $transaction->related();   
                @endphp
                <div class="min-w-screen flex items-center justify-center">
                    <div class="flex flex-wrap items-center w-full lg:w-5/6">

                        {{-- Info box - Profit / Loss --}}
                        <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                            <div class="flex items-center">
                                <div class="flex-grow text-left">
                                    <span class="text-sm block">{{ __('Profit / Loss') }}</span> 
                                </div>
                                <div class="flex-grow text-sm text-right">
                                    @php
                                        $perc = floor($rel->sumCurrency('profitLoss')->divide($rel->sumCurrency('total'))->multiply(new App\Support\Number(100))->getValue());
                                    @endphp
                                    {{ $perc }}% 
                                </div>
                            </div>
                            <x-currency :amount="$rel->sumCurrency('profitLoss')" />
                        </div>

                        {{-- Info box - Profit / Loss per day --}}
                        <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                            <div class="flex items-center">
                                <div class="flex-grow text-left">
                                    <span class="text-sm block">{{ __('Profit / Loss') }}</span> 
                                </div>
                                <div class="flex-grow text-sm text-right">
                                    Per day
                                </div>
                            </div>
                            <x-currency :amount="$rel->sumCurrency('profitLoss')->divide(new App\Support\Number( ceil($rel->avg('hodlDays')) ))" />
                        </div>
                        
                        {{-- Info box - Balance before --}}
                        <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                            <span class="text-sm block">{{ __('Balance Before') }}</span> <x-quantity :quantity="$transaction->cryptoToken->balance( $transaction->time )" />
                        </div>

                        {{-- Info box - Balance after --}}
                        <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                            <span class="text-sm block">{{ __('Balance After') }}</span> <x-quantity :quantity="$transaction->cryptoToken->balance( $transaction->time->addSecond(1) )" />
                        </div>

                        {{-- Info box - Average hodl days --}}
                        <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                            <span class="text-sm block">{{ __('Avg Hodl Days') }}</span> {{ ceil($rel->avg('hodlDays')) }} 
                        </div>   

                        {{-- Info box - Average related buy / sell price --}}
                        <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                            @if ( $transaction->isBuy() )
                                <span class="text-sm block">{{ __('Average Sell Price') }}</span> 
                                <x-currency :amount="$rel->averageSellPrice()" />
                            @else
                                <span class="text-sm block">{{ __('Average Buy Price') }}</span> 
                                <x-currency :amount="$rel->averageBuyPrice()" />
                            @endif     
                        </div>

                        {{-- Info box - Still Hodling --}}
                        @if ( $transaction->isBuy() )
                            <div class="flex-grow p-6 mb-3 mr-3 bg-white shadow-lg rounded-lg text-2xl text-center">
                                <span class="text-sm block">{{ __('Still Hodling') }}</span> 
                                <x-quantity :quantity="$transaction->quantity->subtract( $rel->sumQuantity('quantity') )" />
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Transactions --}}
    <div class="overflow-x-auto">
        <div class="min-w-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
            <div class="w-full lg:w-5/6">
                @if ($rel->count() < 1)
                    <h1 class="text-3xl">No related {{ $transaction->isBuy() ? 'sell':'buy' }} transactions</h1>
                @else

                    <h1 class="text-3xl">Related {{ $transaction->isBuy() ? 'sell':'buy' }} transactions</h1>

                    <div class="bg-white shadow-md rounded my-4">
                        <table class="min-w-max w-full table-fixed md:table-auto">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left border-l-8 border-{{ $rel->first()->colour() }}-500">Date</th>
                                    <th class="py-3 px-6 text-right">Quantity</th>
                                    <th class="py-3 px-6 text-right">Price</th>
                                    <th class="py-3 px-6 text-right">Total</th>
                                    <th class="py-3 px-6 text-center">Hodl Time</th>
                                    <th class="py-3 px-6 text-right">Profit</th>
                                    <th class="py-3 px-6"> </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 text-sm font-light">

                                @foreach ($rel as $related)
                                
                                    <tr class="border-b border-gray-200 hover:bg-{{ $related->colour() }}-100">
                                        <td class="py-3 px-6 text-left border-l-8 border-{{ $related->colour() }}-500">
                                            <span class="whitespace-nowrap">{{ $related->time->format('j F \'y') }}</span>
                                            <span class="whitespace-nowrap text-xs">{{ $related->time->format('h:i:s A') }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-right">
                                            <x-quantity :quantity="$related->quantity" />
                                        </td>
                                        <td class="py-3 px-6 text-right">
                                            <x-currency :amount="$related->price" />
                                        </td>
                                        <td class="py-3 px-6 text-right">
                                            <x-currency :amount="$related->total()" />
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            {{ $related->hodlDays }}d
                                        </td>
                                        <td class="py-3 px-6 text-right">
                                            <x-currency :amount="$related->profitLoss" />
                                        </td>

                                        <td class="py-3 px-2 text-right">
                                            <div class="flex item-center justify-center">
                                                <div class="w-4 mr-2 transform hover:scale-110">
                                                    <a href="{{ route('transaction.edit', ['transaction' => $related->id]) }}" 
                                                        class="text-gray-500 hover:text-red-500" 
                                                        title="Edit this transaction"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="w-4 mr-2 transform hover:scale-110">
                                                    <a href="{{ route('transaction.show', ['transaction' => $related->id]) }}" 
                                                        class="text-gray-500 hover:text-red-500" 
                                                        title="View this transaction"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                </div>                                            
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach

                                <tr class="border-b bg-gray-200 font-bold">
                                    <td class="py-3 px-6 text-left border-l-8 border-{{ $rel->first()->colour() }}-500"> </td>
                                    <td class="py-3 px-6 text-right">
                                        <x-quantity :quantity="$rel->sumQuantity('quantity')" />
                                    </td>
                                    <td class="py-3 px-6 text-right">{{ __('Avg.') }} 
                                        @if ( $transaction->isBuy() )
                                            <x-currency :amount="$rel->averageSellPrice()" />
                                        @else
                                            <x-currency :amount="$rel->averageBuyPrice()" />
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        <x-currency :amount="$rel->sumCurrency('total')" />
                                    </td>
                                    <td class="py-3 px-6 text-center">{{ __('Avg.') }}  {{ ceil($rel->avg('hodlDays')) }}d</td>
                                    <td class="py-3 px-6 text-right">
                                        <x-currency :amount="$rel->sumCurrency('profitLoss')" />
                                    </td>

                                    <td class="py-3 px-2 text-right"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                

                @endif                

            </div>
        </div>
    </div>

</x-app-layout>
