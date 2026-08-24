<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Education</h3>
            <p class="text-sm text-gray-500">Most recent first.</p>
        </div>
        <button
            type="button"
            wire:click="addEducation"
            wire:loading.attr="disabled"
            class="shrink-0 px-3 py-2 text-sm font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50"
        >
            + Add education
        </button>
    </div>

    <div class="space-y-4">
        @foreach ($education as $index => $entry)
            <div wire:key="education-{{ $index }}" class="border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Entry {{ $index + 1 }}</span>
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="moveEducationUp({{ $index }})" wire:loading.attr="disabled" @if ($index === 0) disabled @endif class="p-1 text-gray-400 hover:text-gray-700 disabled:opacity-30" aria-label="Move up">&uarr;</button>
                        <button type="button" wire:click="moveEducationDown({{ $index }})" wire:loading.attr="disabled" @if ($index === count($education) - 1) disabled @endif class="p-1 text-gray-400 hover:text-gray-700 disabled:opacity-30" aria-label="Move down">&darr;</button>
                        <button type="button" wire:click="removeEducation({{ $index }})" wire:loading.attr="disabled" class="p-1 text-gray-400 hover:text-red-600" aria-label="Remove entry">&times;</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" wire:model.live.debounce.750ms="education.{{ $index }}.institution" placeholder="Institution" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="text" wire:model.live.debounce.750ms="education.{{ $index }}.qualification" placeholder="Qualification" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="month" wire:model.live.debounce.750ms="education.{{ $index }}.start_date" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="month" wire:model.live.debounce.750ms="education.{{ $index }}.end_date" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>

                <textarea
                    rows="2"
                    wire:model.live.debounce.750ms="education.{{ $index }}.notes"
                    placeholder="Notes (optional)"
                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
            </div>
        @endforeach

        @if (empty($education))
            <p class="text-sm text-gray-400">No education added yet.</p>
        @endif
    </div>
</div>
