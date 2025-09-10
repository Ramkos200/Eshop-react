<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-500/20 backdrop-blur-md border border-red-400/30 rounded-full font-["Inter"] text-xs text-white uppercase tracking-widest hover:bg-red-500/30 hover:border-red-400/50 active:bg-red-500/40 focus:outline-none focus:ring-2 focus:ring-red-400/50 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
