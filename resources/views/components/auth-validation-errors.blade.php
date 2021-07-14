@props(['errors'])

@if ($errors->any()) 
    <div {{ $attributes->merge(['class' => 'bg-red-700 rounded-lg p-4 text-white']) }}>
        <div class="font-medium">
            {{ __('Something went wrong.') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
