<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm text-white tracking-wider transition-all duration-300']) }}>
    {{ $slot }}
</button>
