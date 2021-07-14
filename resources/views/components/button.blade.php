<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md 
    font-semibold text-xs text-white uppercase tracking-widest 
    hover:bg-yellow-300 hover:text-gray-800 
    active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
