<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-rule rounded-sm font-semibold text-xs text-ink uppercase tracking-widest shadow-sm hover:bg-paper-dim focus:outline-none focus:ring-2 focus:ring-cobalt focus:ring-offset-2 focus:ring-offset-paper disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
