<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Build your CV</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gray-100">
            <header class="bg-white shadow-sm">
                <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
                    <a href="/" wire:navigate class="flex items-center gap-2">
                        <x-application-logo class="w-8 h-8 fill-current text-gray-500" />
                        <span class="font-semibold text-gray-700">{{ config('app.name', 'Laravel') }}</span>
                    </a>

                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-800">
                        Save &amp; exit
                    </a>
                </div>
            </header>

            <main class="max-w-4xl mx-auto px-4 py-6">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
