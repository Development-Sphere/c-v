<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fair Copy') }} — Build your CV</title>

        @include('partials.fonts')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="min-h-screen bg-paper">
            <header class="border-b border-rule bg-paper">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
                    <a href="/" wire:navigate>
                        <x-brand-mark />
                    </a>

                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" wire:navigate class="font-mono text-xs uppercase tracking-wide text-ink-soft hover:text-cobalt transition-colors">
                        Save &amp; exit
                    </a>
                </div>
            </header>

            <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
