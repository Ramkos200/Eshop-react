@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full ps-4 pe-4 py-3 border-l-4 border-white font-["Inter"] text-base font-medium text-white bg-white/10 focus:outline-none focus:bg-white/20 transition duration-150 ease-in-out'
            : 'block w-full ps-4 pe-4 py-3 border-l-4 border-transparent font-["Inter"] text-base font-medium text-gray-300 hover:text-white hover:bg-white/10 hover:border-white/30 focus:outline-none focus:text-white focus:bg-white/10 focus:border-white/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
