<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-md border border-white/30 rounded-full font-["Inter"] text-sm text-white uppercase tracking-widest hover:bg-white/30 hover:border-white/50 focus:bg-white/30 active:bg-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
