<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-ink">Education</h3>
            <p class="text-sm text-ink-soft">Most recent first.</p>
        </div>
        <button
            type="button"
            wire:click="addEducation"
            wire:loading.attr="disabled"
            class="shrink-0 px-3 py-2 text-sm font-medium text-cobalt border border-cobalt rounded-sm hover:bg-cobalt-dim transition-colors"
        >
            + Add education
        </button>
    </div>

    <div class="space-y-4">
        @foreach ($education as $index => $entry)
            <div wire:key="education-{{ $index }}" class="border border-rule rounded-sm p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-mono text-xs uppercase tracking-wide text-ink-soft">Entry {{ $index + 1 }}</span>
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="moveEducationUp({{ $index }})" wire:loading.attr="disabled" @if ($index === 0) disabled @endif class="p-1 text-ink-soft hover:text-ink disabled:opacity-30" aria-label="Move up">&uarr;</button>
                        <button type="button" wire:click="moveEducationDown({{ $index }})" wire:loading.attr="disabled" @if ($index === count($education) - 1) disabled @endif class="p-1 text-ink-soft hover:text-ink disabled:opacity-30" aria-label="Move down">&darr;</button>
                        <button type="button" wire:click="removeEducation({{ $index }})" wire:loading.attr="disabled" class="p-1 text-ink-soft hover:text-red-600" aria-label="Remove entry">&times;</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" wire:model.live.debounce.750ms="education.{{ $index }}.institution" placeholder="Institution" class="rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt" />
                    <input type="text" wire:model.live.debounce.750ms="education.{{ $index }}.qualification" placeholder="Qualification" class="rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt" />
                    <input type="month" wire:model.live.debounce.750ms="education.{{ $index }}.start_date" class="rounded-sm border-rule text-ink shadow-sm text-sm font-mono focus:border-cobalt focus:ring-cobalt" />
                    <input type="month" wire:model.live.debounce.750ms="education.{{ $index }}.end_date" class="rounded-sm border-rule text-ink shadow-sm text-sm font-mono focus:border-cobalt focus:ring-cobalt" />
                </div>

                <textarea
                    rows="2"
                    wire:model.live.debounce.750ms="education.{{ $index }}.notes"
                    placeholder="Notes (optional)"
                    class="block w-full rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt"
                ></textarea>
            </div>
        @endforeach

        @if (empty($education))
            <p class="text-sm text-ink-soft/70">No education added yet.</p>
        @endif
    </div>
</div>
