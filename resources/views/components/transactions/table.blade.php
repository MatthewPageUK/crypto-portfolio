{{--
    transactions - collection of transactions
    totals - bool, show totals in footer
    ignore - array, ignore these fields
--}}
@props(['transactions', 'totals', 'ignore'])

{{-- List of transactions in a table --}}
@if ($transactions->count() > 0)

    <div class="overflow-x-auto">
        <div class="min-w-screen flex items-center justify-center overflow-hidden">
            <div class="w-full mx-12">
                <div class="bg-white shadow-lg rounded my-4">
                    <table class="min-w-max w-full table-fixed md:table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">

                                {{-- Date --}}
                                <th class="py-3 px-6 text-left border-l-8 border-{{ $transactions->first()->colour() }}-500">Date</th>

                                {{-- Type --}}
                                @if ( ! in_array('type', $ignore) )
                                    <th class="py-3 px-6 text-center hidden md:table-cell">Type</th>
                                @endif

                                {{-- Quantity --}}
                                <th class="py-3 px-6 text-right">Quantity</th>

                                {{-- Price --}}
                                <th class="py-3 px-6 text-right">Price</th>

                                {{-- Total --}}
                                <th class="py-3 px-6 text-right hidden md:table-cell">Total</th>

                                {{-- Hodl Days --}}
                                @if ( ! in_array('hodlDays', $ignore) )
                                    <th class="py-3 px-6 text-center">Hodl Time</th>
                                @endif

                                {{-- Profit and Loss --}}
                                @if ( ! in_array('profitLoss', $ignore) )
                                    <th class="py-3 px-6 text-right">Profit</th>
                                @endif

                                {{-- Buttons --}}
                                <th class="py-3 px-6 text-center"> </th>

                            </tr>
                        </thead>
                        <tbody class="text-gray-800">

                            @foreach ($transactions as $transaction)

                                <tr class="@if (!$transaction->note) border-b @endif border-gray-200 hover:bg-{{ $transaction->colour() }}-100">

                                    {{-- Date --}}
                                    <td class="py-3 px-6 text-left border-l-8 border-{{ $transaction->colour() }}-500">
                                        <span class="whitespace-nowrap">{{ $transaction->time->format('j F \'y') }}</span>
                                        <span class="whitespace-nowrap text-xs">{{ $transaction->time->format('h:i:s A') }}</span>
                                    </td>

                                    {{-- Type --}}
                                    @if ( ! in_array('type', $ignore) )
                                        <td class="py-3 px-6 text-center hidden md:table-cell">
                                            {{ ucwords($transaction->type) }}
                                        </td>
                                    @endif

                                    {{-- Quantity --}}
                                    <td class="py-3 px-6 text-right">
                                        <x-quantity :quantity="$transaction->quantity" />
                                    </td>

                                    {{-- Price --}}
                                    <td class="py-3 px-6 text-right">
                                        <x-currency :amount="$transaction->price" />
                                    </td>

                                    {{-- Total --}}
                                    <td class="py-3 px-6 text-right hidden md:table-cell">
                                        <x-currency :amount="$transaction->total()" />
                                    </td>

                                    {{-- Hodl Days --}}
                                    @if ( ! in_array('hodlDays', $ignore) )
                                        <td class="py-3 px-6 text-center">
                                            {{ $transaction->hodlDays }}d
                                        </td>
                                    @endif

                                    {{-- Profit and Loss --}}
                                    @if ( ! in_array('profitLoss', $ignore) )
                                        <td class="py-3 px-6 text-right">
                                            <x-currency :amount="$transaction->profitLoss" />
                                        </td>
                                    @endif

                                    {{-- Buttons --}}
                                    <td class="py-3 px-2 text-right flex item-center justify-center text-gray-500">
                                        <x-transactions.link-edit :transaction="$transaction" class="mr-1 transform hover:scale-110">
                                            <x-icons.pen class="hover:text-green-500" />
                                        </x-transactions.link-edit>

                                        <x-transactions.link-delete :transaction="$transaction" class="mr-1 transform hover:scale-110">
                                            <x-icons.bin class="hover:text-red-500" />
                                        </x-transactions.link-delete>

                                        <x-transactions.link :transaction="$transaction" class="transform hover:scale-110">
                                            <x-icons.eye class="hover:text-green-500" />
                                        </x-transactions.link>
                                    </td>
                                </tr>

                                {{-- Note --}}
                                @if ($transaction->note)
                                    <tr class="border-b border-gray-200">
                                        <td colspan="{{ 8 - sizeof($ignore) }}" class="text-sm pb-3 px-6 border-l-8 border-{{ $transaction->colour() }}-500">
                                            {{ $transaction->note }}
                                        </td>
                                    </tr>
                                @endif

                            @endforeach

                            {{-- The totals footer - only really works for related transactions...  --}}
                            @if ($totals)
                                <tr class="border-b bg-gray-200 font-bold">
                                    {{-- Date --}}
                                    <td class="py-3 px-6 text-left border-l-8 border-{{ $transactions->first()->colour() }}-500"> </td>

                                    {{-- Type --}}
                                    @if ( ! in_array('type', $ignore) )
                                        <td> </td>
                                    @endif

                                    {{-- Quantity --}}
                                    <td class="py-3 px-6 text-right">
                                        <x-quantity :quantity="$transactions->sumQuantity('quantity')" />
                                    </td>

                                    {{-- Avg price --}}
                                    <td class="py-3 px-6 text-right">{{ __('Avg.') }}
                                        @if ( $transactions->first()->isSell() )
                                            <x-currency :amount="$transactions->averageSellPrice()" />
                                        @else
                                            <x-currency :amount="$transactions->averageBuyPrice()" />
                                        @endif
                                    </td>

                                    {{-- Total --}}
                                    <td class="py-3 px-6 text-right">
                                        <x-currency :amount="$transactions->sumCurrency('total')" />
                                    </td>

                                    {{-- Hodl Days --}}
                                    @if ( ! in_array('hodlDays', $ignore) )
                                        <td class="py-3 px-6 text-center">{{ __('Avg.') }}  {{ ceil($transactions->avg('hodlDays')) }}d</td>
                                    @endif

                                    {{-- Profit and Loss --}}
                                    @if ( ! in_array('profitLoss', $ignore) )
                                        <td class="py-3 px-6 text-right">
                                            <x-currency :amount="$transactions->sumCurrency('profitLoss')" />
                                        </td>
                                    @endif

                                    {{-- Buttons --}}
                                    <td> </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endif
