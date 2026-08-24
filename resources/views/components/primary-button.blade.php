<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-cobalt border border-transparent rounded-sm font-semibold text-xs text-paper uppercase tracking-widest hover:bg-ink focus:outline-none focus:ring-2 focus:ring-cobalt focus:ring-offset-2 focus:ring-offset-paper transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
