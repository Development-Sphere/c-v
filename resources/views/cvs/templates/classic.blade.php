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
    <body class="font-serif text-gray-900 bg-gray-200">
        @php
            $formatMonth = fn (?string $v) => $v ? \Carbon\Carbon::parse($v.'-01')->format('M Y') : '';
            $dateRange = fn (array $entry) => trim(($formatMonth($entry['start_date'] ?? null)).' – '.(($entry['current'] ?? false) ? 'Present' : $formatMonth($entry['end_date'] ?? null)), ' –');
        @endphp

        <div class="max-w-[210mm] mx-auto bg-white shadow-lg min-h-[297mm] p-10">
            <header class="text-center border-b-2 border-gray-800 pb-4">
                <h1 class="text-3xl font-bold tracking-wide">{{ ($cv->personal_info['name'] ?? '') ?:'Your Name' }}</h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ collect([$cv->personal_info['email'] ?? null, $cv->personal_info['phone'] ?? null, $cv->personal_info['location'] ?? null])->filter()->implode('  |  ') }}
                </p>
                @if (count($cv->personal_info['links'] ?? []) > 0)
                    <p class="mt-1 text-sm text-gray-600">
                        {{ collect($cv->personal_info['links'])->pluck('url')->filter()->implode('  |  ') }}
                    </p>
                @endif
            </header>

            @if ($cv->summary['raw'] ?? null)
                <section class="mt-6">
                    <h2 class="text-sm font-bold uppercase tracking-widest border-b border-gray-300 pb-1">Summary</h2>
                    <p class="mt-2 text-sm leading-relaxed">{{ $cv->summary['raw'] }}</p>
                </section>
            @endif

            @if (count($cv->experience) > 0)
                <section class="mt-6">
                    <h2 class="text-sm font-bold uppercase tracking-widest border-b border-gray-300 pb-1">Experience</h2>
                    <div class="mt-3 space-y-4">
                        @foreach ($cv->experience as $entry)
                            <div class="cv-entry">
                                <div class="flex items-baseline justify-between gap-4">
                                    <p class="font-bold">{{ $entry['title'] ?: 'Role' }}@if ($entry['company'] ?? null), {{ $entry['company'] }}@endif</p>
                                    @if ($dateRange($entry))<p class="text-xs text-gray-500 shrink-0">{{ $dateRange($entry) }}</p>@endif
                                </div>
                                @if ($entry['location'] ?? null)<p class="text-sm text-gray-600 italic">{{ $entry['location'] }}</p>@endif
                                @if (count($entry['bullets'] ?? []) > 0)
                                    <ul class="mt-2 space-y-1 text-sm list-disc list-inside">
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

            @if (count($cv->education) > 0)
                <section class="mt-6">
                    <h2 class="text-sm font-bold uppercase tracking-widest border-b border-gray-300 pb-1">Education</h2>
                    <div class="mt-3 space-y-3">
                        @foreach ($cv->education as $entry)
                            <div class="cv-entry">
                                <div class="flex items-baseline justify-between gap-4">
                                    <p class="font-bold">{{ $entry['qualification'] ?: 'Qualification' }}</p>
                                    @if ($dateRange($entry))<p class="text-xs text-gray-500 shrink-0">{{ $dateRange($entry) }}</p>@endif
                                </div>
                                @if ($entry['institution'] ?? null)<p class="text-sm text-gray-600 italic">{{ $entry['institution'] }}</p>@endif
                                @if ($entry['notes'] ?? null)<p class="mt-1 text-sm">{{ $entry['notes'] }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($cv->skills) > 0)
                <section class="mt-6">
                    <h2 class="text-sm font-bold uppercase tracking-widest border-b border-gray-300 pb-1">Skills</h2>
                    <p class="mt-2 text-sm">{{ implode(', ', $cv->skills) }}</p>
                </section>
            @endif
        </div>
    </body>
</html>
