<x-app-layout title=" - {{ __('Edit transaction') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit transaction') }}
        </h2>
    </x-slot>

    <x-form-card>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('transaction.update', $transaction) }}">
            @csrf

            <!-- Token -->
            <div>
                <x-label for="crypto_token_id" value="{{ __('Token') }}" />
                <select class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50" id="crypto_token_id" name="crypto_token_id">
                    <option value="">{{ __('Choose your token')}}</option>
                    @foreach($tokens as $token)
                        <option value="{{ $token->id }}" {{ ($transaction->crypto_token_id === $token->id) ? 'selected' : '' }}>{{ $token->symbol }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Transaction type -->
            <div class="mt-4">
                <x-label for="type" value="{{ __('Type') }}" />
                <div class="mt-1">
                    <span class="mr-3 text-sm"><input class="rounded-md shadow-sm border-gray-300 focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50" type="radio" name="type" value="buy" {{ ($transaction->isBuy()) ? 'checked' : '' }} /> Buy </span>
                    <span class="text-sm"><input class="rounded-md shadow-sm border-gray-300 focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50" type="radio" name="type" value="sell" {{ ($transaction->isSell()) ? 'checked' : '' }} /> Sell </span>
                </div>
            </div>

            <!-- Date and time -->
            <div class="mt-4">
                <x-label for="time" :value="__('Date / Time')" />

                <x-input id="time" class="block mt-1 w-full" type="datetime-local" name="time" :value="old('time', $transaction->time->format('Y-m-d\TH:i:s'))" required />
            </div>

            <!-- Quantity -->
            <div class="mt-4">
                <x-label for="quantity" :value="__('Quantity')" />

                <x-input id="quantity" class="block mt-1 w-full" type="text" name="quantity" :value="old('quantity', $transaction->quantity->getValue())" required autofocus />
            </div>

            <!-- Price paid -->
            <div class="mt-4">
                <x-label for="price" :value="__('Price')" />

                <x-input id="price" class="block mt-1 w-full" type="text" name="price" :value="old('price', $transaction->price->getValue())" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('token.show', $transaction->crypto_token_id) }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Save changes') }}
                </x-button>
            </div>
        </form>
    </x-form-card>
</x-app-layout>
