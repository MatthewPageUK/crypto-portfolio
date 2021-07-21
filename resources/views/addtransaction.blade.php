<x-app-layout title=" - {{ __( (($transType==='buy')?'Buy':'Sell') . ' ' . $token->name) }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __( (($transType==='buy')?'Buy':'Sell') . ' ' . $token->name) }}
        </h2>
    </x-slot>

    <x-form-card>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('transaction.store') }}">
            @csrf

            <!-- Token -->
            <input type="hidden" name="crypto_token_id" value="{{ $token->id }}">

            <!-- Transaction type -->
            <input type="hidden" name="type" value="{{ ($transType==='buy')?'buy':'sell' }}">

            <!-- Date and time -->
            <div>
                <x-label for="time" :value="__('Date / Time')" />

                <x-input id="time" class="block mt-1 w-full" type="datetime-local" name="time" :value="old('time', now()->format('Y-m-d\TH:i:s'))" required />
            </div>

            <!-- Quantity -->
            <div class="mt-4">
                <x-label for="quantity" :value="__('Quantity')" />

                <x-input id="quantity" class="block mt-1 w-full" type="text" name="quantity" :value="old('quantity')" required autofocus />
            </div>

            <!-- Price paid -->
            <div class="mt-4">
                <x-label for="price" :value="__('Price')" />

                <x-input id="price" class="block mt-1 w-full" type="text" name="price" :value="old('price')" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('token.show', $token->id) }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ml-4">
                    {{ __( (($transType==='buy')?'Buy':'Sell') . ' ' . $token->symbol) }}
                </x-button>
            </div>
        </form>
    </x-form-card>
</x-app-layout>
