<!-- Transaction card  -->

<div class="w-full bg-white shadow-md rounded overflow-hidden">
    <table class="min-w-max w-full table-fixed md:table-auto">
        <tbody class="text-gray-800 text-sm font-light">
            {{-- ID --}}
            <tr class="border-b border-gray-200">
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Ref') }}</th>
                <td class="w-full py-3 px-6 text-left">{{ $transaction->id }}</td>
            </tr>
            {{-- Token --}}
            <tr class="border-b border-gray-200">
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Token') }}</th>
                <td class="py-3 px-6 text-left font-bold">
                    <x-tokens.link :token="$transaction->token" class="flex items-center">
                        {{ $transaction->token->symbol }}
                        <x-icons.eye class="hover:text-green-500 ml-1" />
                    </x-tokens.link>
                </td>
            </tr>
            {{-- Date --}}
            <tr class="border-b border-gray-200">
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Date') }}</th>
                <td class="py-3 px-6 text-left font-bold">
                    <span class="whitespace-nowrap">{{ $transaction->time->format('l j F \'y') }}</span>
                    <span class="whitespace-nowrap text-xs">{{ $transaction->time->format('h:i:s A') }}</span>
                </td>
            </tr>
            {{-- Type --}}
            <tr class="border-b border-gray-200">
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Type') }}</th>
                <td class="py-3 px-6 text-left font-bold">{{ ucwords($transaction->type); }}</td>
            </tr>
            {{-- Price --}}
            <tr class="border-b border-gray-200">
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Price') }}</th>
                <td class="py-3 px-6 text-left font-bold"><x-currency :amount="$transaction->price" /></td>
            </tr>
            {{-- Quantity --}}
            <tr class="border-b border-gray-200">
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Quantity') }}</th>
                <td class="py-3 px-6 text-left font-bold"><x-quantity :quantity="$transaction->quantity" /></td>
            </tr>
            {{-- Total --}}
            <tr>
                <th class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal py-3 px-6 text-right border-l-8 border-{{ $transaction->colour() }}-500">{{ __('Total') }}</th>
                <td class="py-3 px-6 text-left font-bold"><x-currency :amount="$transaction->total()" /></td>
            </tr>
        </tbody>
    </table>
</div>
