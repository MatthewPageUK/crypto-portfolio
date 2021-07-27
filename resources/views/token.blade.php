<x-app-layout title=" - {{ __($token->symbol . ' - ' . $token->name) }}">
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex flex-grow">
                <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">

                    {{-- Title --}}
                    {{ __($token->symbol . ' - ' . $token->name) }} 

                    {{-- Edit link --}}
                    <x-tokens.link-edit :token="$token" class="w-4 inline-block ml-2 text-gray-500 transform hover:scale-110">
                        <x-icons.pen class="h-4 w-4 hover:text-green-500" />
                    </x-tokens.link-edit>

                    {{-- Delete link --}}
                    <x-tokens.link-delete :token="$token" class="w-4 inline-block text-gray-500 transform hover:scale-110">
                        <x-icons.bin class="h-4 w-4 hover:text-red-500" />
                    </x-tokens.link-delete>

                </h2>   
            </div>
            <div class="flex flex-shrink">

                {{-- Buy link --}}
                <x-tokens.link-buy :token="$token" class="flex flex-shrink items-center text-green-700 hover:text-green-500">
                    <span class="flex-grow mr-1">Buy</span>
                    <x-icons.plus class="mr-4 w-5" />
                </x-tokens.link-buy>

                {{-- Sell link --}}
                @if( $token->balance()->getValue() > 0 )
                    <x-tokens.link-sell :token="$token" class="flex flex-shrink items-center text-red-700 hover:text-red-500">
                        <span class="flex-grow mr-1">Sell</span>
                        <x-icons.minus class="mr-4 w-5" />
                    </x-tokens.link-buy>
                @endif

            </div>
    </x-slot>

    <div class="min-w-screen flex items-center justify-center">
        <div class="flex items-center w-full lg:w-5/6">

            {{-- Token balance --}}
            <x-widgets.stats-box title="{{ __('Balance') }}">
                <x-quantity :quantity="$token->balance()" />
            </x-widgets.stats-box>

            {{-- Average buy price --}}
            <x-widgets.stats-box title="{{ __('Avg. Buy price') }}">
                <x-currency :amount="$token->averageBuyPrice()" />
            </x-widgets.stats-box>

            {{-- Average hodl price --}}
            <x-widgets.stats-box title="{{ __('Avg. Hodl price') }}">
                <x-currency :amount="$token->averageHodlBuyPrice()" />
            </x-widgets.stats-box>

            {{-- Average sell price --}}
            <x-widgets.stats-box title="{{ __('Avg. Sell price') }}">
                <x-currency :amount="$token->averageSellPrice()" />
            </x-widgets.stats-box>

        </div>
    </div>

    {{-- Transactions table --}}
    <x-transactions.table :transactions="$token->transactions" :totals="false" :ignore="['hodlDays', 'profitLoss']"/>

</x-app-layout>








