@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-["Inter"] text-sm font-medium text-white']) }}>
    {{ $value ?? $slot }}
</label>
