<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>



    <div class="overflow-x-auto">
        <div class="min-w-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
            <div class="w-full">
                <div xclass="bg-white shadow-md rounded my-6">















                    <div class="w-full flex flex-wrap justify-center">
                        <div class="w-64 p-6 m-5 bg-white shadow-lg rounded-lg">
                            <p>
                                <a href="{{ route('addtoken') }}" title="Add a new token" class="text-blue-500 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-500 hover:text-gray-800 w-1/4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                            </p>
                        </div>
                
                        @foreach ($tokens as $token)
                            <div class="w-64 p-6 m-5 bg-white shadow-lg rounded-lg text-center">
                                <h1 class="text-5xl mb-2">
                                    <a href="{{ route('token', $token->id) }}" title="View {{ $token->name }} transactions">{{ $token->symbol }}</a>
                                </h1>
                                <h2 class="text-sm mb-2 text-gray-500">
                                    {{ $token->name }}
                                </h2>
                
                                @if( $token->balance > 0 )
                                    <p class="p-2 rounded-lg bg-green-500 font-black text-white">
                                        {{ $token->balance; }}
                                    </p>
                                @else
                                    <p class="p-2 rounded-lg bg-gray-200 font-black text-white">
                                        {{ $token->balance; }}
                                    </p>
                                @endif
                
                                <p class="mt-3 text-sm uppercase flex items-center text-center">
                                    <a href="{{ route('buy', $token->id) }}" class="mr-2 flex items-center flex-grow border border-gray-500 rounded-lg p-1" title="Buy {{ $token->symbol }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Buy
                                    </a>
                
                                    @if( $token->balance > 0 )
                                        <a href="{{ route('sell', $token->id) }}" class="ml-2 flex items-center flex-grow border border-gray-500 rounded-lg p-1" title="Sell {{ $token->symbol }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-700 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Sell
                                        </a>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                
                    </div>













                </div>
            </div>
        </div>
    </div>










</x-app-layout>
