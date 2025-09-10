<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-transparent backdrop-blur-md border border-white/30 rounded-full font-["Inter"] text-xs text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
