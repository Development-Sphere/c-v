<div class="space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-ink">Professional summary</h3>
        <p class="text-sm text-ink-soft">A short paragraph introducing yourself. Two or three sentences is plenty.</p>
    </div>

    <div>
        <label for="summary-raw" class="sr-only">Summary</label>
        <textarea
            id="summary-raw"
            rows="6"
            wire:model.live.debounce.750ms="summary.raw"
            placeholder="e.g. Backend engineer with 6 years of experience building payments infrastructure..."
            class="block w-full rounded-sm border-rule text-ink font-mono text-sm shadow-sm focus:border-cobalt focus:ring-cobalt"
        ></textarea>
        <p class="mt-1 font-mono text-[11px] uppercase tracking-wide text-ink-soft">Rough draft — write it however it comes out</p>
    </div>

    <div>
        @include('livewire.cv-builder.partials.ai-button', [
            'action' => 'polishSummary',
            'label' => 'Improve with AI',
        ])
        @include('livewire.cv-builder.partials.ai-error')
    </div>

    @if (filled($summary['polished'] ?? null))
        <div class="rounded-sm border border-seal bg-seal-dim p-4 space-y-3">
            <div class="flex items-center gap-2">
                <x-seal-glyph animate class="w-5 h-5" />
                <p class="font-mono text-xs font-medium uppercase tracking-wide text-seal">Fair copy</p>
            </div>
            <p class="font-serif text-[15px] text-ink leading-relaxed">{{ $summary['polished'] }}</p>
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    wire:click="acceptPolishedSummary"
                    wire:loading.attr="disabled"
                    class="px-3 py-1.5 text-sm font-medium text-paper bg-cobalt rounded-sm hover:bg-ink transition-colors"
                >
                    Use this
                </button>
                <button
                    type="button"
                    wire:click="discardPolishedSummary"
                    wire:loading.attr="disabled"
                    class="px-3 py-1.5 text-sm font-medium text-ink-soft hover:text-ink"
                >
                    Discard
                </button>
            </div>
        </div>
    @endif
</div>
