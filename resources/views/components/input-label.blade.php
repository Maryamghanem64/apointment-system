@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-white/60 text-sm font-medium mb-2']) }}>
    {{ $value ?? $slot }}
</label>
