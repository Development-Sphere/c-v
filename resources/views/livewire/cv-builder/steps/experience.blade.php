<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Work experience</h3>
            <p class="text-sm text-gray-500">Most recent role first.</p>
        </div>
        <button
            type="button"
            wire:click="addExperience"
            wire:loading.attr="disabled"
            class="shrink-0 px-3 py-2 text-sm font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50"
        >
            + Add role
        </button>
    </div>

    <div class="space-y-4">
        @foreach ($experience as $index => $entry)
            <div wire:key="experience-{{ $index }}" class="border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Role {{ $index + 1 }}</span>
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="moveExperienceUp({{ $index }})" wire:loading.attr="disabled" @if ($index === 0) disabled @endif class="p-1 text-gray-400 hover:text-gray-700 disabled:opacity-30" aria-label="Move up">&uarr;</button>
                        <button type="button" wire:click="moveExperienceDown({{ $index }})" wire:loading.attr="disabled" @if ($index === count($experience) - 1) disabled @endif class="p-1 text-gray-400 hover:text-gray-700 disabled:opacity-30" aria-label="Move down">&darr;</button>
                        <button type="button" wire:click="removeExperience({{ $index }})" wire:loading.attr="disabled" class="p-1 text-gray-400 hover:text-red-600" aria-label="Remove role">&times;</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" wire:model.live.debounce.750ms="experience.{{ $index }}.title" placeholder="Job title" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="text" wire:model.live.debounce.750ms="experience.{{ $index }}.company" placeholder="Company" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="text" wire:model.live.debounce.750ms="experience.{{ $index }}.location" placeholder="Location" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <div class="flex items-center gap-2">
                        <input type="month" wire:model.live.debounce.750ms="experience.{{ $index }}.start_date" class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <input type="month" wire:model.live.debounce.750ms="experience.{{ $index }}.end_date" @if($entry['current'] ?? false) disabled @endif class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100" />
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" wire:model.live="experience.{{ $index }}.current" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    I currently work here
                </label>

                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Highlights</span>
                        <button type="button" wire:click="addExperienceBullet({{ $index }})" wire:loading.attr="disabled" class="text-sm text-indigo-600 hover:text-indigo-800">+ Add bullet</button>
                    </div>

                    <div class="mt-2 space-y-2">
                        @foreach ($entry['bullets'] ?? [] as $bulletIndex => $bullet)
                            <div wire:key="experience-{{ $index }}-bullet-{{ $bulletIndex }}" class="flex gap-2">
                                <input
                                    type="text"
                                    wire:model.live.debounce.750ms="experience.{{ $index }}.bullets.{{ $bulletIndex }}"
                                    placeholder="e.g. Led migration to a new billing platform, cutting failed charges by 30%"
                                    class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <button type="button" wire:click="removeExperienceBullet({{ $index }}, {{ $bulletIndex }})" wire:loading.attr="disabled" class="px-2 text-gray-400 hover:text-red-600" aria-label="Remove bullet">&times;</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        @if (empty($experience))
            <p class="text-sm text-gray-400">No roles added yet.</p>
        @endif
    </div>
</div>
