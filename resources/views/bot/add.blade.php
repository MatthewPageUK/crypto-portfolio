<x-app-layout title=" - {{ __('Trading Bot') }}">
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex flex-grow">
                <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">

                    {{-- Title --}}
                    {{ __('Trading Bot') }}


                </h2>
            </div>
            <div class="">
                <div class="text-xs text-green-500">Connected to</div>
                <div><img src="/img/coinbase.png" style="height: 15px" /></div>
            </div>
    </x-slot>

    <form method="POST" action="{{ route('bot.store') }}">
        @csrf

        <input type="hidden" name="name" value="noname" />

        <div class="flex items-center justify-center mx-12"
            x-data="{ direction: '', price: 0, quantity: 0, profit: 10, loss: 5, token: '', funds: 4528, botimg: '' }"
        >
            <div class="flex items-center w-full lg:w-5/6 mt-16 mb-64">
                <div class="flex w-full bg-white py-8 px-24 pb-16 rounded-lg shadow-lg"
                    style="background-image: url('/img/bot.jpg'); background-repeat: no-repeat; background-position: top right; min-height: 500px;"
                >
                    <div class="w-3/4 pr-16">
                        <h1 class="font-bold text-3xl mb-4">Start a new bot</h1>

                        <!-- Direction -->
                        <div class="mb-4">
                            <x-label for="direction" value="{{ __('Which way do you think the market is going to move?') }}" />
                            <div
                                :class="direction == 'up' ? 'bg-gray-100 font-bold' : ''"
                                class="my-2 p-4 border-2 border-gray-100 rounded-lg hover:border-green-100 hover:bg-green-100 flex items-center"
                                x-on:click="direction = 'up'"
                            >
                                <input name="direction" type="radio" value="up" x-model="direction" class="mr-2">
                                <span>{{ __('To the moon baby') }}</span>
                            </div>

                            <div
                                :class="direction == 'down' ? 'bg-gray-100 font-bold' : ''"
                                class="my-2 p-4 border-2 border-gray-100 rounded-lg hover:border-red-100 hover:bg-red-100 flex items-center"
                                x-on:click="direction = 'down'"
                            >
                                <input name="direction" type="radio" value="down" x-model="direction" class="mr-2">
                                <span>{{ __('The whales are dumping') }}</span>
                            </div>

                        </div>

                        <div x-show="direction != ''">

                            <!-- Token -->
                            <div class="mb-4">
                                <select class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                    id="token_id" name="token_id"
                                    x-model="token"
                                >
                                    <option value="">{{ __('Choose your market')}}</option>
                                    @foreach($tokens as $token)
                                        <option x-text="'{{ $token->symbol }} - {{ $token->name }}'" value="{{ $token->id }}">{{ $token->symbol }} - {{ $token->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="token != ''">

                                <!-- Buy Price -->
                                <div class="mb-4" x-show="direction == 'up'">
                                    <x-label for="price" :value="__('Buy price')" />

                                    <x-input id="price" class="block mt-1 w-full" type="text" name="price" required
                                        x-model="price"
                                    />

                                    <div class="flex mt-2 space-x-2">

                                        <button
                                            class="px-3 py-2 text-sm bg-gray-100 rounded-full hover:bg-yellow-400 hover:text-white"
                                            x-on:click.prevent="price = 0.38"
                                        >Price now £0.38</button>

                                        <button
                                            class="px-3 py-2 text-sm bg-gray-100 rounded-full hover:bg-yellow-400 hover:text-white"
                                            x-on:click.prevent="price = 0.75"
                                        >Avg buy price £0.75</button>

                                        <button
                                            class="px-3 py-2 text-sm bg-gray-100 rounded-full hover:bg-yellow-400 hover:text-white"
                                            x-on:click.prevent="price = 0.65"
                                        >Last buy price £0.65</button>

                                    </div>
                                </div>

                                <!-- Sell Price -->
                                {{-- <div x-show="direction == 'down'">
                                    <x-label for="bot_sell_price" :value="__('Short price')" />

                                    <x-input id="bot_sell_price" class="block mt-1 w-full" type="text" name="bot_sell_price" :value="old('bot_sell_price')" required
                                        x-model="price"
                                    />
                                </div> --}}

                                <div x-show="price > 0">

                                    <!-- Quantity -->
                                    <div>
                                        <x-label for="quantity" :value="__('Quantity')" />

                                        <div class="relative">

                                            <x-input id="quantity" class="block mt-1 w-full" type="text" name="quantity" required x-model="quantity" />

                                            <span class="absolute top-0 right-0 text-xs mt-1 bg-red-500 text-white p-2 rounded-full" x-show="( quantity * price ) > funds">Insufficient funds on Exchange</span>

                                        </div>

                                        <div class="flex mt-2 space-x-2">

                                            <button
                                                class="px-3 py-2 text-sm bg-gray-100 rounded-full hover:bg-yellow-400 hover:text-white"
                                                x-on:click.prevent="quantity = (funds/price).toFixed(2)"
                                            >Max</button>

                                            <button
                                                class="px-3 py-2 text-sm bg-gray-100 rounded-full hover:bg-yellow-400 hover:text-white"
                                                x-on:click.prevent="quantity = (funds/price/2).toFixed(2)"
                                            >50%</button>

                                            <button
                                                class="px-3 py-2 text-sm bg-gray-100 rounded-full hover:bg-yellow-400 hover:text-white"
                                                x-on:click.prevent="quantity = (funds/price/10).toFixed(2)"
                                            >10%</button>

                                        </div>

                                    </div>

                                    <div x-show="quantity > 0">

                                        <!-- Minimum Profit -->
                                        <div class="mt-4" >
                                            <x-label for="profit" value="{{ __('Minimum profit to take') }}" />

                                            <div class="flex space-x-4 items-center">
                                                <input type="range" min="1" max="100" step="1" value="1" class="form-range w-full h-6 p-0 bg-transparent focus:outline-none focus:ring-0 focus:shadow-none"
                                                    id="profit" name="profit" x-model="profit">

                                                <div class="p-2 bg-green-100 rounded-full text-sm">
                                                    <span x-text="profit"></span>%
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="profit > 0">

                                            <!-- Stop Loss -->
                                            <div class="mt-4">
                                                <x-label for="loss" value="{{ __('Maximum loss to take') }}" />

                                                <div class="flex space-x-4 items-center">
                                                    <input type="range" min="1" max="100" step="1" value="1" class="form-range w-full h-6 p-0 bg-transparent focus:outline-none focus:ring-0 focus:shadow-none"
                                                        id="loss" name="loss" x-model="loss">

                                                    <div class="p-2 bg-red-100 rounded-full text-sm">
                                                        <span x-text="loss"></span>%
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="w-1/4 ml-16 text-center" style="margin-top: 400px" x-show="price * quantity != 0">

                        <table class="w-full text-sm">
                            <tr class="hover:bg-gray-100">
                                <th class="text-left p-2">Investment</th>
                                <td class="text-right p-2">&pound;<span x-text="(quantity * price).toFixed(2)"></span></td>
                            </tr>
                            <tr class="hover:bg-red-100">
                                <th class="text-left p-2">Maximum loss</th>
                                <td class="text-right p-2">
                                    <span class="text-xs">(<span x-text="loss"></span>%)</span>
                                    &pound;<span x-text="(((price*quantity)/100)*loss).toFixed(2)"></span>

                                </td>
                            </tr>
                            <tr class="hover:bg-green-100">
                                <th class="text-left p-2">Minimum gain</th>
                                <td class="text-right p-2">
                                    <span class="text-xs">(<span x-text="profit"></span>%)</span>
                                    &pound;<span x-text="(((price*quantity)/100)*profit).toFixed(2)"></span>

                                </td>
                            </tr>
                            <tr class="hover:bg-green-100">
                                <th class="text-left p-2">Maximum gain</th>
                                <td class="text-right p-2">Unlimited</td>
                            </tr>
                            <tr class="hover:bg-gray-100">
                                <th class="text-left p-2">Trailing stop loss</th>
                                <td class="text-right p-2">Enabled</td>
                            </tr>
                        </table>

                        <div>
                            <div class="mt-4 p-4 bg-red-100 text-sm font-bold rounded-lg hover:bg-red-500 hover:text-white">
                                Your prediction is the price is going
                                    <span class="upper" x-text="direction"></span> by
                                    <span class="upper" x-text="profit"></span>% to
                                    &pound;<span x-text="(price+((price/100)*profit)).toFixed(2) "></span>.

                                If it drops to
                                    &pound;<span x-text="(price-((price/100)*loss)).toFixed(2) "></span> you lose
                                    &pound;<span x-text="(((price*quantity)/100)*loss).toFixed(2)"></span>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('bot.index') }}">
                                    {{ __('Cancel') }}
                                </a>

                                <x-button class="ml-4">
                                    {{ __('Release Bot') }}
                                </x-button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
