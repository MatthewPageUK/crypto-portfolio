<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex flex-wrap">
        <div class="w-1/6 p-6 m-5 bg-white shadow-lg rounded-lg">
            <p><a href="{{ route('addtoken') }}" class="text-blue-500 hover:text-blue-800">Add Token</a></p>
        </div>
        @foreach ($tokens as $token)
            <div class="w-1/6 p-6 m-5 bg-white shadow-lg rounded-lg">
                <h1>{{ $token->symbol }}</h1>
                <h2>{{ $token->name }}</h2>
                <p>Quantity : 0</p>
                <p><a href="{{ route('addtransaction', $token->id) }}" class="text-blue-500 hover:text-blue-800">Add transaction</a></p>
            </div>
        @endforeach
    </div>

</x-app-layout>



