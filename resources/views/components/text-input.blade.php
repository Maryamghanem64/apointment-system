@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input-dark rounded-xl px-4 py-3 w-full transition-all duration-300']) !!}>
