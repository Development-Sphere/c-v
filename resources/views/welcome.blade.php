<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} - Build a CV in minutes</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <header class="max-w-4xl mx-auto w-full px-6 py-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-application-logo class="w-8 h-8 fill-current text-gray-500" />
                    <span class="font-semibold text-gray-700">{{ config('app.name', 'Laravel') }}</span>
                </div>

                @if (Route::has('login'))
                    <livewire:welcome.navigation />
                @endif
            </header>

            <main class="flex-1 flex items-center">
                <div class="max-w-2xl mx-auto w-full px-6 py-12 text-center">
                    <h1 class="text-3xl sm:text-4xl font-semibold text-gray-900">
                        Build a professional CV in minutes
                    </h1>
                    <p class="mt-4 text-lg text-gray-500">
                        Fill in a short guided form, pick a template, and download a polished CV.
                        No account required to get started.
                    </p>

                    <form method="POST" action="{{ route('cv.store') }}" class="mt-8">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            Build my CV
                        </button>
                    </form>
                </div>
            </main>

            <footer class="py-8 text-center text-sm text-gray-400">
                {{ config('app.name', 'Laravel') }}
            </footer>
        </div>
    </body>
</html>
