@php

    $colour = $transaction->isBuy() ? 'green' : 'red';

@endphp
<!-- Transaction card  -->
<div class="overflow-x-auto">
    <div class="min-w-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
        <div class="w-full lg:w-5/6">
            <div class="bg-white shadow-md rounded my-4">
                <table class="min-w-max w-full table-fixed md:table-auto">
                    <tbody class="text-gray-800 text-sm font-light">
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">ID</th>
                            <td class="w-full py-3 px-6 text-left">{{ $transaction->id }}</td>
                        </tr>
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">Token</th>
                            <td class="py-3 px-6 text-left">
                                <a href="{{ route('token.show', ['token' => $transaction->cryptoToken]) }}">
                                    {{ $transaction->cryptoToken->symbol }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline text-gray-600 h-4 w-4 hover:text-green-500" fill="none" viewBox="0 2 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>                                    
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">Date</th>
                            <td class="py-3 px-6 text-left">
                                <span class="whitespace-nowrap">{{ $transaction->time->format('j F \'y') }}</span>
                                <span class="whitespace-nowrap text-xs">{{ $transaction->time->format('h:i:s A') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">Type</th>
                            <td class="py-3 px-6 text-left">{{ ucwords($transaction->type); }}</td>
                        </tr>
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">Price</th>
                            <td class="py-3 px-6 text-left">{{ $transaction->price->humanReadable() }}</td>
                        </tr>
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">Quantity</th>
                            <td class="py-3 px-6 text-left">{{ $transaction->quantity->humanReadable() }}</td>
                        </tr>
                        <tr>
                            <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal    py-3 px-6 text-right border-l-8 border-{{ $colour }}-500">Total</th>
                            <td class="py-3 px-6 text-left">{{ $transaction->total()->humanReadable() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>