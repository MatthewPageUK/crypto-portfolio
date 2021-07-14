@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50']) !!}>
