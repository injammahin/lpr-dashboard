<button {{ $attributes->merge([
    'class' =>
        'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium 
     transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring 
     focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none 
     bg-black text-white hover:bg-gray-800 h-10 px-4 py-2'
]) }}>
    {{ $slot }}
</button>