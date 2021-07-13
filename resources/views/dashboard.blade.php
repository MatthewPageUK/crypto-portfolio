<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex-grow">
                <a href="{{ route('token.create') }}" title="Add a new token" class="flex items-center text-right hover:text-green-500">
                    <span class="flex-grow">Add Token</span> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </a>
            </div>
    </x-slot>



    <div class="overflow-x-auto">
        <div class="min-w-screen bg-gray-100 flex items-center justify-center bg-gray-100 font-sans overflow-hidden">
            <div class="w-full">
                <div xclass="bg-white shadow-md rounded my-6">
                    <div class="w-full flex flex-wrap justify-center">
                        <!-- Tokens -->
                        @foreach ($tokens as $token)
                            <div class="w-64 p-6 m-5 bg-white shadow-lg rounded-lg text-center">
                                <h1 class="text-5xl mb-2">
                                    <a href="{{ route('token.show', $token->id) }}" title="View {{ $token->name }} transactions">{{ $token->symbol }}</a>
                                </h1>
                                <h2 class="text-sm mb-2 text-gray-500">
                                    {{ $token->name }}
                                </h2>
                
                                @if( $token->balance > 0 )
                                    <p class="p-2 rounded-lg bg-yellow-200 font-black text-green-600">
                                        {{ $token->balance; }}
                                    </p>
                                @else
                                    <p class="p-2 rounded-lg bg-gray-200 font-black text-white">
                                        {{ $token->balance; }}
                                    </p>
                                @endif
                
                                <p class="mt-3 flex items-center text-center">
                                    <a href="{{ route('token.buy', $token->id) }}" class="mr-2 flex items-center justify-center flex-grow p-1 text-green-700 hover:text-green-500" title="Buy {{ $token->symbol }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Buy
                                    </a>
                
                                    @if( $token->balance > 0 )
                                        <a href="{{ route('token.sell', $token->id) }}" class="ml-2 flex items-center justify-center flex-grow p-1 text-red-700 hover:text-red-500" title="Sell {{ $token->symbol }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
