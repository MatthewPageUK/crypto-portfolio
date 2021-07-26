<x-app-layout title=" - {{ __('Trading Diary') }}">
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex flex-grow">
                <h2 class="flex-grow font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Trading Diary') }} 
                </h2>   
            </div>
    </x-slot>

    <div class="min-w-screen flex items-center justify-center mt-8">
        <div class="flex items-center w-full lg:w-5/6">
            <div class="w-full bg-white py-8 px-24 rounded-lg shadow-lg">
                @php
                    $month = '';
                    $day = '';
                @endphp
                @foreach ($transactions as $transaction)

                    @if ( $month != $transaction->time->format('F') )
                        @php
                            $month = $transaction->time->format('F')
                        @endphp
                        <h1 class="text-5xl text-center font-serif text-gray-400 italic">{{ $month }}</h1>
                        <p class="text-center tracking-widest text-gray-400 mb-5 pb-2 text-sm border-b border-gray-400" style="margin-top: -15px">{{ $transaction->time->format('Y') }}</p>
                    @endif

                    @if ( $day != $transaction->time->format('l') )
                        @php
                            $day = $transaction->time->format('l')
                        @endphp
                        <h2 class="text-2xl mb-4 mt-6 font-serif text-gray-400">{{ $day }} {{ $transaction->time->format('jS') }}</h1>
                    @endif                    

                    <p class="font-cursive text-2xl mb-4">
                        <span class="text-xl">{{ $transaction->time->format('g:ia') }}</span> - 

                        {{
                            $transaction->pastTenseType(1) . 
                            ' ' . 
                            $transaction->quantity->humanReadable()+0 . 
                            ' ' . 
                            $transaction->cryptoToken->symbol . 
                            ' at ' . 
                            $transaction->price->humanReadable()                    
                        }}

                        @if ($transaction->isSell())
                            and made a {{ ($transaction->related()->sumCurrency('profitLoss')->getValue() < 0) ? 'loss' : 'profit' }} of <x-currency :amount="$transaction->related()->sumCurrency('profitLoss')" />
                        @endif
       
                        </p>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>
