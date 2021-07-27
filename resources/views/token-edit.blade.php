<x-app-layout title=" - {{ __('Edit token') }} {{ $token->symbol }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit token') }} {{ $token->symbol }}
        </h2>
    </x-slot>

    <x-form-card>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('token.update', ['token' => $token->id]) }}">
            @csrf

            <!-- Symbol -->
            <div>
                <x-label for="symbol" :value="__('Symbol')" />

                <x-input id="symbol" class="block mt-1 w-full" type="text" name="symbol" :value="old('symbol', $token->symbol)" required autofocus />
            </div>

            <!-- Name -->
            <div class="mt-4">
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $token->name)" required />
            </div>


            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Update Token') }}
                </x-button>
            </div>
        </form>
    </x-form-card>
</x-app-layout>
