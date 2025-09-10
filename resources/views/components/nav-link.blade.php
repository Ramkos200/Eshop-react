@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-4 py-2 border-b-2 border-white text-sm font-["Inter"] leading-5 text-white focus:outline-none focus:border-white/80 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-["Inter"] leading-5 text-gray-300 hover:text-white hover:border-white/30 focus:outline-none focus:text-white focus:border-white/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
