<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Fair Copy') }} — Build a CV in minutes</title>

        @include('partials.fonts')

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-paper text-ink">
        <div class="min-h-screen flex flex-col">
            <header class="border-b border-rule">
                <div class="max-w-5xl mx-auto w-full px-6 py-5 flex items-center justify-between">
                    <x-brand-mark />

                    @if (Route::has('login'))
                        <livewire:welcome.navigation />
                    @endif
                </div>
            </header>

            <main class="flex-1">
                {{-- Hero --}}
                <section class="max-w-5xl mx-auto px-6 pt-16 pb-20 sm:pt-24 sm:pb-28">
                    <p class="font-mono text-xs tracking-[0.2em] uppercase text-seal">No account needed to start</p>

                    <h1 class="mt-4 font-serif text-4xl sm:text-5xl leading-[1.1] tracking-tight text-ink max-w-2xl">
                        Write the rough draft.<br>We hand you the fair copy.
                    </h1>

                    <p class="mt-5 text-lg text-ink-soft max-w-xl leading-relaxed">
                        Fill in a short guided form, let AI tighten the phrasing without inventing anything,
                        and pick a template that reads like a real document, not a web page.
                    </p>

                    <div class="mt-8 flex items-center gap-4">
                        <form method="POST" action="{{ route('cv.store') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center px-6 py-3 text-base font-medium text-paper bg-cobalt rounded hover:bg-ink transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-cobalt focus-visible:ring-offset-2 focus-visible:ring-offset-paper"
                            >
                                Build my CV
                            </button>
                        </form>
                        <span class="font-mono text-xs text-ink-soft">free to start · account only needed to export</span>
                    </div>

                    {{-- Signature element: raw draft -> AI-polished fair copy, joined by the seal --}}
                    <div class="mt-20 relative">
                        <div class="grid grid-cols-1 md:grid-cols-[1fr_104px_1fr] gap-6 md:gap-0 items-stretch">
                            <div class="border border-rule bg-paper-dim rounded-sm p-6 md:rounded-r-none md:border-r-0">
                                <p class="font-mono text-[11px] tracking-[0.2em] uppercase text-ink-soft">Rough draft</p>
                                <p class="mt-3 font-mono text-sm leading-relaxed text-ink-soft">
                                    i worked on the backend team for 3 years. did a lot of api stuff and
                                    helped fix bugs. also mentored some new hires.
                                </p>
                            </div>

                            <div class="hidden md:flex items-center justify-center relative z-10">
                                <div class="absolute inset-y-0 left-1/2 w-px bg-rule"></div>
                                <div class="relative flex flex-col items-center gap-1.5 bg-paper px-3">
                                    <x-seal-glyph animate class="w-11 h-11" />
                                    <span class="font-mono text-[9px] tracking-[0.15em] uppercase text-seal">Polished</span>
                                </div>
                            </div>

                            {{-- Mobile seal, shown between the two panels --}}
                            <div class="flex md:hidden items-center gap-3 -my-1">
                                <div class="flex-1 h-px bg-rule"></div>
                                <x-seal-glyph animate class="w-9 h-9" />
                                <div class="flex-1 h-px bg-rule"></div>
                            </div>

                            <div class="border border-rule bg-white rounded-sm p-6 shadow-[0_1px_2px_rgba(28,27,34,0.04)] md:rounded-l-none">
                                <p class="font-mono text-[11px] tracking-[0.2em] uppercase text-seal">Fair copy</p>
                                <p class="mt-3 font-serif text-[15px] leading-relaxed text-ink">
                                    Backend engineer with three years building and maintaining core APIs,
                                    with a track record of resolving critical production issues and
                                    mentoring new hires.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- How it works: a real sequence, numbering earns its keep here --}}
                <section class="border-t border-rule bg-paper-dim">
                    <div class="max-w-5xl mx-auto px-6 py-16">
                        <p class="font-mono text-xs tracking-[0.2em] uppercase text-ink-soft">How it works</p>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
                            @foreach ([
                                ['n' => '01', 'title' => 'Fill in the basics', 'body' => 'A short guided form — contact details, experience, education, skills. Nothing you have not typed a dozen times before.'],
                                ['n' => '02', 'title' => 'Improve with AI', 'body' => 'Polish rough phrasing on request. Facts, dates and numbers stay exactly as you wrote them.'],
                                ['n' => '03', 'title' => 'Pick a template', 'body' => 'Switch freely — your content stays put, only the layout changes. Preview updates as you type.'],
                                ['n' => '04', 'title' => 'Download the PDF', 'body' => 'Export a document built for printing and screens alike. Come back and edit any time.'],
                            ] as $item)
                                <div>
                                    <p class="font-mono text-sm text-seal">{{ $item['n'] }}</p>
                                    <h3 class="mt-2 font-serif text-lg text-ink">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-sm text-ink-soft leading-relaxed">{{ $item['body'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-rule">
                <div class="max-w-5xl mx-auto px-6 py-8 flex items-center justify-between">
                    <x-brand-mark class="opacity-80" />
                    <p class="font-mono text-xs text-ink-soft">&copy; {{ date('Y') }}</p>
                </div>
            </footer>
        </div>
    </body>
</html>
