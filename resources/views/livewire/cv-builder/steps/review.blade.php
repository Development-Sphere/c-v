<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Review</h3>
        <p class="text-sm text-gray-500">Here's what your CV will include.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-5 text-sm">
        <div>
            <h4 class="font-semibold text-gray-800">{{ $personalInfo['name'] ?: 'Your name' }}</h4>
            <p class="text-gray-500">
                {{ collect([$personalInfo['email'] ?? null, $personalInfo['phone'] ?? null, $personalInfo['location'] ?? null])->filter()->implode(' · ') ?: 'No contact details yet' }}
            </p>
            @if (! empty($personalInfo['links']))
                <p class="text-gray-500 mt-1">
                    {{ collect($personalInfo['links'])->pluck('url')->filter()->implode(' · ') }}
                </p>
            @endif
        </div>

        @if (filled($summary['raw'] ?? null))
            <div>
                <h5 class="font-semibold text-gray-700 uppercase text-xs tracking-wide mb-1">Summary</h5>
                <p class="text-gray-600">{{ $summary['raw'] }}</p>
            </div>
        @endif

        @if (count($experience) > 0)
            <div>
                <h5 class="font-semibold text-gray-700 uppercase text-xs tracking-wide mb-1">Experience</h5>
                <ul class="space-y-1 text-gray-600">
                    @foreach ($experience as $entry)
                        <li>{{ $entry['title'] ?: 'Untitled role' }}@if ($entry['company']) at {{ $entry['company'] }}@endif</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($education) > 0)
            <div>
                <h5 class="font-semibold text-gray-700 uppercase text-xs tracking-wide mb-1">Education</h5>
                <ul class="space-y-1 text-gray-600">
                    @foreach ($education as $entry)
                        <li>{{ $entry['qualification'] ?: 'Untitled qualification' }}@if ($entry['institution']) at {{ $entry['institution'] }}@endif</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($skills) > 0)
            <div>
                <h5 class="font-semibold text-gray-700 uppercase text-xs tracking-wide mb-1">Skills</h5>
                <p class="text-gray-600">{{ implode(', ', $skills) }}</p>
            </div>
        @endif

        <div>
            <h5 class="font-semibold text-gray-700 uppercase text-xs tracking-wide mb-1">Template</h5>
            <p class="text-gray-600 capitalize">{{ $template }}</p>
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700 mb-2">Live preview</p>
        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-100" style="height: 60vh;">
            <iframe
                src="{{ route('cv.preview', $cv) }}?v={{ $previewVersion }}"
                title="CV preview"
                class="w-full h-full"
            ></iframe>
        </div>
    </div>
    </div>

    <div class="border-t pt-4">
        @auth
            <a
                href="{{ route('cv.export', $cv) }}"
                class="inline-block w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
            >
                Export PDF
            </a>
        @else
            <div class="rounded-md bg-indigo-50 p-4">
                <p class="text-sm text-indigo-900">
                    Create a free account to save this CV and download it as a PDF.
                </p>
                <a
                    href="{{ route('register') }}"
                    wire:navigate
                    class="mt-2 inline-block px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                >
                    Create free account
                </a>
            </div>
        @endauth
    </div>
</div>
