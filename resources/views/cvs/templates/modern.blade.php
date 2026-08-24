<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ ($cv->personal_info['name'] ?? '') ?:'CV' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
        <style>{!! \App\Support\CompiledAssets::css() !!}</style>
        <style>
            @page { margin: 0; }
            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; margin: 0; }
                .cv-entry { break-inside: avoid; }
            }
        </style>
    </head>
    <body class="font-serif text-gray-800 bg-gray-200">
        @php
            $formatMonth = fn (?string $v) => $v ? \Carbon\Carbon::parse($v.'-01')->format('M Y') : '';
            $dateRange = fn (array $entry) => trim(($formatMonth($entry['start_date'] ?? null)).' – '.(($entry['current'] ?? false) ? 'Present' : $formatMonth($entry['end_date'] ?? null)), ' –');
        @endphp

        <div class="max-w-[210mm] mx-auto bg-white shadow-lg flex min-h-[297mm]">
            <aside class="w-1/3 bg-slate-800 text-slate-100 p-8">
                <h1 class="text-2xl font-bold leading-tight">{{ ($cv->personal_info['name'] ?? '') ?:'Your Name' }}</h1>

                <div class="mt-6 space-y-1 text-sm text-slate-300 break-words">
                    @if ($cv->personal_info['email'] ?? null)<p>{{ $cv->personal_info['email'] }}</p>@endif
                    @if ($cv->personal_info['phone'] ?? null)<p>{{ $cv->personal_info['phone'] }}</p>@endif
                    @if ($cv->personal_info['location'] ?? null)<p>{{ $cv->personal_info['location'] }}</p>@endif
                    @foreach ($cv->personal_info['links'] ?? [] as $link)
                        @if ($link['url'] ?? null)<p>{{ $link['label'] ?: $link['url'] }}</p>@endif
                    @endforeach
                </div>

                @if (count($cv->skills) > 0)
                    <div class="mt-8">
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Skills</h2>
                        <ul class="mt-2 space-y-1 text-sm">
                            @foreach ($cv->skills as $skill)
                                <li>{{ $skill }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (count($cv->education) > 0)
                    <div class="mt-8">
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Education</h2>
                        <div class="mt-2 space-y-3 text-sm">
                            @foreach ($cv->education as $entry)
                                <div class="cv-entry">
                                    <p class="font-medium">{{ $entry['qualification'] ?: 'Qualification' }}</p>
                                    @if ($entry['institution'] ?? null)<p class="text-slate-300">{{ $entry['institution'] }}</p>@endif
                                    @if ($dateRange($entry))<p class="text-slate-400 text-xs">{{ $dateRange($entry) }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>

            <main class="w-2/3 p-8">
                @if ($cv->summary['raw'] ?? null)
                    <section class="mb-8">
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Summary</h2>
                        <p class="mt-2 text-sm leading-relaxed">{{ $cv->summary['raw'] }}</p>
                    </section>
                @endif

                @if (count($cv->experience) > 0)
                    <section>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Experience</h2>
                        <div class="mt-3 space-y-5">
                            @foreach ($cv->experience as $entry)
                                <div class="cv-entry">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <p class="font-semibold text-slate-900">{{ $entry['title'] ?: 'Role' }}</p>
                                        @if ($dateRange($entry))<p class="text-xs text-slate-400 shrink-0">{{ $dateRange($entry) }}</p>@endif
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        {{ $entry['company'] ?? '' }}{{ ($entry['company'] ?? null) && ($entry['location'] ?? null) ? ' · ' : '' }}{{ $entry['location'] ?? '' }}
                                    </p>
                                    @if (count($entry['bullets'] ?? []) > 0)
                                        <ul class="mt-2 space-y-1 text-sm list-disc list-inside text-slate-700">
                                            @foreach ($entry['bullets'] as $bullet)
                                                @if (trim($bullet))<li>{{ $bullet }}</li>@endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </body>
</html>
