@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-["Inter"] text-sm text-green-400']) }}>
        {{ $status }}
    </div>
@endif
