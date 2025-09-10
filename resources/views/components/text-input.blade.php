@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'bg-white/10 backdrop-blur-md border border-white/20 text-white font-["Inter"] rounded-lg focus:border-white/40 focus:ring-white/40 placeholder-gray-400']) }}>
