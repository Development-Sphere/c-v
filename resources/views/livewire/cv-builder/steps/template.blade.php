<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-ink">Choose a template</h3>
        <p class="text-sm text-ink-soft">You can switch templates any time without losing your content.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach ($templates as $cvTemplate)
            <button
                type="button"
                wire:key="template-{{ $cvTemplate->key }}"
                wire:click="selectTemplate('{{ $cvTemplate->key }}')"
                wire:loading.attr="disabled"
                @class([
                    'text-left border rounded-sm p-4 transition-colors',
                    'border-cobalt ring-1 ring-cobalt bg-cobalt-dim/40' => $template === $cvTemplate->key,
                    'border-rule hover:border-ink-soft' => $template !== $cvTemplate->key,
                ])
            >
                <p class="font-serif text-base text-ink">{{ $cvTemplate->name }}</p>
                @if ($cvTemplate->description)
                    <p class="text-sm text-ink-soft mt-1">{{ $cvTemplate->description }}</p>
                @endif
                @if ($template === $cvTemplate->key)
                    <p class="mt-2 font-mono text-xs uppercase tracking-wide text-cobalt">Selected</p>
                @endif
            </button>
        @endforeach
    </div>

    <div>
        <p class="font-mono text-xs uppercase tracking-wide text-ink-soft mb-2">Live preview</p>
        <div class="border border-rule rounded-sm overflow-hidden bg-paper-dim" style="height: 70vh;">
            <iframe
                src="{{ route('cv.preview', $cv) }}?v={{ $previewVersion }}"
                title="CV preview"
                class="w-full h-full"
            ></iframe>
        </div>
    </div>
</div>
