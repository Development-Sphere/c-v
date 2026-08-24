<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-ink">Review</h3>
        <p class="text-sm text-ink-soft">Here's what your CV will include.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-5 text-sm">
        <div>
            <h4 class="font-serif text-lg text-ink">{{ $personalInfo['name'] ?: 'Your name' }}</h4>
            <p class="text-ink-soft">
                {{ collect([$personalInfo['email'] ?? null, $personalInfo['phone'] ?? null, $personalInfo['location'] ?? null])->filter()->implode(' · ') ?: 'No contact details yet' }}
            </p>
            @if (! empty($personalInfo['links']))
                <p class="text-ink-soft mt-1">
                    {{ collect($personalInfo['links'])->pluck('url')->filter()->implode(' · ') }}
                </p>
            @endif
        </div>

        @if (filled($summary['raw'] ?? null))
            <div>
                <h5 class="font-mono font-medium text-ink-soft uppercase text-xs tracking-wide mb-1">Summary</h5>
                <p class="text-ink">{{ $summary['raw'] }}</p>
            </div>
        @endif

        @if (count($experience) > 0)
            <div>
                <h5 class="font-mono font-medium text-ink-soft uppercase text-xs tracking-wide mb-1">Experience</h5>
                <ul class="space-y-1 text-ink">
                    @foreach ($experience as $entry)
                        <li>{{ $entry['title'] ?: 'Untitled role' }}@if ($entry['company']) at {{ $entry['company'] }}@endif</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($education) > 0)
            <div>
                <h5 class="font-mono font-medium text-ink-soft uppercase text-xs tracking-wide mb-1">Education</h5>
                <ul class="space-y-1 text-ink">
                    @foreach ($education as $entry)
                        <li>{{ $entry['qualification'] ?: 'Untitled qualification' }}@if ($entry['institution']) at {{ $entry['institution'] }}@endif</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($skills) > 0)
            <div>
                <h5 class="font-mono font-medium text-ink-soft uppercase text-xs tracking-wide mb-1">Skills</h5>
                <p class="text-ink">{{ implode(', ', $skills) }}</p>
            </div>
        @endif

        <div>
            <h5 class="font-mono font-medium text-ink-soft uppercase text-xs tracking-wide mb-1">Template</h5>
            <p class="text-ink capitalize">{{ $template }}</p>
        </div>
    </div>

    <div>
        <p class="font-mono text-xs uppercase tracking-wide text-ink-soft mb-2">Live preview</p>
        <div class="border border-rule rounded-sm overflow-hidden bg-paper-dim" style="height: 60vh;">
            <iframe
                src="{{ route('cv.preview', $cv) }}?v={{ $previewVersion }}"
                title="CV preview"
                class="w-full h-full"
            ></iframe>
        </div>
    </div>
    </div>

    <div class="border-t border-rule pt-5">
        @auth
            <a
                href="{{ route('cv.export', $cv) }}"
                class="inline-block w-full sm:w-auto text-center px-5 py-2.5 text-sm font-medium text-paper bg-cobalt rounded-sm hover:bg-ink transition-colors"
            >
                Export PDF
            </a>
        @else
            <div class="rounded-sm border border-cobalt bg-cobalt-dim p-4">
                <p class="text-sm text-ink">
                    Create a free account to save this CV so you don't lose it, and download it as a PDF.
                </p>
                <a
                    href="{{ route('register') }}"
                    wire:navigate
                    class="mt-3 inline-block px-4 py-2 text-sm font-medium text-paper bg-cobalt rounded-sm hover:bg-ink transition-colors"
                >
                    Create free account
                </a>
            </div>
        @endauth
    </div>
</div>
