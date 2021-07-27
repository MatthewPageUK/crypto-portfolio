@php
    $related = $transaction->related();   
@endphp
<x-app-layout title=" - {{ $transaction->humanReadable() }}">
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex flex-grow">
                <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">

                    {{-- Title --}}
                    {{ $transaction->humanReadable() }}

                    {{-- Edit transaction --}}
                    <x-transactions.link-edit :transaction="$transaction" class="w-4 inline-block ml-2 text-gray-500 transform hover:scale-110" >
                        <x-icons.pen class="hover:text-green-500" />
                    </x-transactions.link-edit>

                    {{-- Delete transaction --}}
                    <x-transactions.link-delete :transaction="$transaction" class="w-4 inline-block text-gray-500 transform hover:scale-110" >
                        <x-icons.bin class="hover:text-red-500" />
                    </x-transactions.link-delete>

                </h2>   
            </div>
    </x-slot>

    {{-- Info Boxes --}}
    <div class="min-w-screen flex items-center justify-center my-8">
        <div class="flex items-top w-full lg:w-5/6">
            <div class="flex-1">

                {{-- Transaction details --}}
                <x-transactions.card :transaction="$transaction" />

            </div>
            <div class="flex-1">
                <div class="min-w-screen flex items-center justify-center">
                    <div class="flex flex-wrap items-center w-full lg:w-5/6">

                        {{-- Info box - Profit / Loss (todo) --}}
                        @php
                            $perc = floor($related->sumCurrency('profitLoss')->divide($related->sumCurrency('total'))->multiply(new App\Support\Number(100))->getValue());
                        @endphp                       
                        <x-widgets.stats-box title="{{ __('Profit / Loss') }} ({{ $perc }}%)" class="mr-3 mb-3 mt-0 ml-0">
                            <x-currency :amount="$related->sumCurrency('profitLoss')" />
                        </x-widgets.stats-box>

                        {{-- Info box - Profit / Loss per day --}}
                        <x-widgets.stats-box title="{{ __('Profit / Loss per day') }}" class="mr-3 mb-3 mt-0 ml-0">
                            <x-currency :amount="$related->sumCurrency('profitLoss')->divide(new App\Support\Number( ceil($related->avg('hodlDays')) ))" />
                        </x-widgets.stats-box>

                        {{-- Info box - Average hodl days --}}
                        <x-widgets.stats-box title="{{ __('Avg. Hodl Days') }}" class="mr-3 mb-3 mt-0 ml-0">
                            {{ ceil($related->avg('hodlDays')) }} 
                        </x-widgets.stats-box>   

                        {{-- Info box - Average related buy / sell price --}}
                        @if ( $transaction->isBuy() )
                            <x-widgets.stats-box title="{{ __('Avg. Sell Price') }}" class="mr-3 mb-3 mt-0 ml-0">
                                <x-currency :amount="$related->averageSellPrice()" />
                            </x-widgets.stats-box>
                        @else
                            <x-widgets.stats-box title="{{ __('Avg. Buy Price') }}" class="mr-3 mb-3 mt-0 ml-0">
                                <x-currency :amount="$related->averageBuyPrice()" />
                            </x-widgets.stats-box>
                        @endif     
             
                        {{-- Info box - Still Hodling --}}
                        @if ( $transaction->isBuy() )
                            <x-widgets.stats-box title="{{ __('Still Hodling') }}" class="mr-3 mb-3 mt-0 ml-0">
                                <x-quantity :quantity="$transaction->quantity->subtract( $related->sumQuantity('quantity') )" />
                            </x-widgets.stats-box>
                        @endif
                        
                        {{-- Info box - Balance before --}}
                        <x-widgets.stats-box title="{{ __('Balance before') }}" class="mr-3 mb-3 mt-0 ml-0">
                            <x-quantity :quantity="$transaction->cryptoToken->balance( $transaction->time )" />
                        </x-widgets.stats-box>

                        {{-- Info box - Balance after --}}
                        <x-widgets.stats-box title="{{ __('Balance after') }}" class="mr-3 mb-3 mt-0 ml-0">
                            <x-quantity :quantity="$transaction->cryptoToken->balance( $transaction->time->addSecond(1) )" />
                        </x-widgets.stats-box>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Transactions --}}
    <x-transactions.table :transactions="$related" :totals="true" :ignore="['type']" />

</x-app-layout>
