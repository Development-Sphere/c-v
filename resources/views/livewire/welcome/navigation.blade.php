<nav class="flex items-center gap-2">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded px-3 py-2 text-sm font-medium text-ink hover:text-cobalt transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-cobalt"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="rounded px-3 py-2 text-sm font-medium text-ink-soft hover:text-ink transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-cobalt"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="rounded px-3 py-2 text-sm font-medium text-cobalt hover:text-ink transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-cobalt"
            >
                Sign up
            </a>
        @endif
    @endauth
</nav>
