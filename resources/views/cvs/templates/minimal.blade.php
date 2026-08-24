<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $cv->personal_info['name'] ?: 'CV' }}</title>
        <style>{!! \App\Support\CompiledAssets::css() !!}</style>
        <style>
            @page { margin: 0; }
            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; margin: 0; }
                .cv-entry { break-inside: avoid; }
            }
        </style>
    </head>
    <body class="font-sans text-gray-700 bg-gray-200">
        @php
            $formatMonth = fn (?string $v) => $v ? \Carbon\Carbon::parse($v.'-01')->format('M Y') : '';
            $dateRange = fn (array $entry) => trim(($formatMonth($entry['start_date'] ?? null)).' – '.(($entry['current'] ?? false) ? 'Present' : $formatMonth($entry['end_date'] ?? null)), ' –');
        @endphp

        <div class="max-w-[210mm] mx-auto bg-white shadow-lg min-h-[297mm] px-12 py-14">
            <header>
                <h1 class="text-2xl font-light tracking-wide text-gray-900">{{ $cv->personal_info['name'] ?: 'Your Name' }}</h1>
                <p class="mt-2 text-xs uppercase tracking-widest text-gray-400">
                    {{ collect([$cv->personal_info['email'] ?? null, $cv->personal_info['phone'] ?? null, $cv->personal_info['location'] ?? null])->filter()->implode('   ·   ') }}
                </p>
                @if (count($cv->personal_info['links'] ?? []) > 0)
                    <p class="mt-1 text-xs uppercase tracking-widest text-gray-400">
                        {{ collect($cv->personal_info['links'])->pluck('url')->filter()->implode('   ·   ') }}
                    </p>
                @endif
            </header>

            @if ($cv->summary['raw'] ?? null)
                <section class="mt-10">
                    <p class="text-sm leading-relaxed text-gray-600">{{ $cv->summary['raw'] }}</p>
                </section>
            @endif

            @if (count($cv->experience) > 0)
                <section class="mt-10">
                    <h2 class="text-xs uppercase tracking-widest text-gray-400">Experience</h2>
                    <div class="mt-4 space-y-6">
                        @foreach ($cv->experience as $entry)
                            <div class="cv-entry">
                                <div class="flex items-baseline justify-between gap-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $entry['title'] ?: 'Role' }}</p>
                                    @if ($dateRange($entry))<p class="text-xs text-gray-400 shrink-0">{{ $dateRange($entry) }}</p>@endif
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $entry['company'] ?? '' }}{{ ($entry['company'] ?? null) && ($entry['location'] ?? null) ? ', ' : '' }}{{ $entry['location'] ?? '' }}
                                </p>
                                @if (count($entry['bullets'] ?? []) > 0)
                                    <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                        @foreach ($entry['bullets'] as $bullet)
                                            @if (trim($bullet))<li>— {{ $bullet }}</li>@endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($cv->education) > 0)
                <section class="mt-10">
                    <h2 class="text-xs uppercase tracking-widest text-gray-400">Education</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($cv->education as $entry)
                            <div class="cv-entry">
                                <div class="flex items-baseline justify-between gap-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $entry['qualification'] ?: 'Qualification' }}</p>
                                    @if ($dateRange($entry))<p class="text-xs text-gray-400 shrink-0">{{ $dateRange($entry) }}</p>@endif
                                </div>
                                @if ($entry['institution'] ?? null)<p class="text-sm text-gray-500">{{ $entry['institution'] }}</p>@endif
                                @if ($entry['notes'] ?? null)<p class="mt-1 text-sm text-gray-600">{{ $entry['notes'] }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($cv->skills) > 0)
                <section class="mt-10">
                    <h2 class="text-xs uppercase tracking-widest text-gray-400">Skills</h2>
                    <p class="mt-3 text-sm text-gray-600">{{ implode('   ·   ', $cv->skills) }}</p>
                </section>
            @endif
        </div>
    </body>
</html>
