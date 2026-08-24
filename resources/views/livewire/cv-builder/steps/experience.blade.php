<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-ink">Work experience</h3>
            <p class="text-sm text-ink-soft">Most recent role first.</p>
        </div>
        <button
            type="button"
            wire:click="addExperience"
            wire:loading.attr="disabled"
            class="shrink-0 px-3 py-2 text-sm font-medium text-cobalt border border-cobalt rounded-sm hover:bg-cobalt-dim transition-colors"
        >
            + Add role
        </button>
    </div>

    @include('livewire.cv-builder.partials.ai-error')

    <div class="space-y-4">
        @foreach ($experience as $index => $entry)
            <div wire:key="experience-{{ $index }}" class="border border-rule rounded-sm p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-mono text-xs uppercase tracking-wide text-ink-soft">Role {{ $index + 1 }}</span>
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="moveExperienceUp({{ $index }})" wire:loading.attr="disabled" @if ($index === 0) disabled @endif class="p-1 text-ink-soft hover:text-ink disabled:opacity-30" aria-label="Move up">&uarr;</button>
                        <button type="button" wire:click="moveExperienceDown({{ $index }})" wire:loading.attr="disabled" @if ($index === count($experience) - 1) disabled @endif class="p-1 text-ink-soft hover:text-ink disabled:opacity-30" aria-label="Move down">&darr;</button>
                        <button type="button" wire:click="removeExperience({{ $index }})" wire:loading.attr="disabled" class="p-1 text-ink-soft hover:text-red-600" aria-label="Remove role">&times;</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" wire:model.live.debounce.750ms="experience.{{ $index }}.title" placeholder="Job title" class="rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt" />
                    <input type="text" wire:model.live.debounce.750ms="experience.{{ $index }}.company" placeholder="Company" class="rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt" />
                    <input type="text" wire:model.live.debounce.750ms="experience.{{ $index }}.location" placeholder="Location" class="rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt" />
                    <div class="flex items-center gap-2">
                        <input type="month" wire:model.live.debounce.750ms="experience.{{ $index }}.start_date" class="flex-1 rounded-sm border-rule text-ink shadow-sm text-sm font-mono focus:border-cobalt focus:ring-cobalt" />
                        <input type="month" wire:model.live.debounce.750ms="experience.{{ $index }}.end_date" @if($entry['current'] ?? false) disabled @endif class="flex-1 rounded-sm border-rule text-ink shadow-sm text-sm font-mono focus:border-cobalt focus:ring-cobalt disabled:bg-paper-dim" />
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-ink-soft">
                    <input type="checkbox" wire:model.live="experience.{{ $index }}.current" class="rounded-sm border-rule text-cobalt focus:ring-cobalt" />
                    I currently work here
                </label>

                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-ink-soft">Highlights</span>
                        <div class="flex items-center gap-3">
                            @if (count(array_filter($entry['bullets'] ?? [], fn ($b) => trim($b) !== '')) > 0)
                                @include('livewire.cv-builder.partials.ai-button', [
                                    'action' => 'improveBullets('.$index.')',
                                    'target' => 'improveBullets',
                                    'label' => 'Improve with AI',
                                ])
                            @endif
                            <button type="button" wire:click="addExperienceBullet({{ $index }})" wire:loading.attr="disabled" class="text-sm font-medium text-cobalt hover:text-ink">+ Add bullet</button>
                        </div>
                    </div>

                    <div class="mt-2 space-y-2">
                        @foreach ($entry['bullets'] ?? [] as $bulletIndex => $bullet)
                            <div wire:key="experience-{{ $index }}-bullet-{{ $bulletIndex }}" class="flex gap-2">
                                <input
                                    type="text"
                                    wire:model.live.debounce.750ms="experience.{{ $index }}.bullets.{{ $bulletIndex }}"
                                    placeholder="e.g. Led migration to a new billing platform, cutting failed charges by 30%"
                                    class="flex-1 rounded-sm border-rule text-ink shadow-sm text-sm font-mono focus:border-cobalt focus:ring-cobalt"
                                />
                                <button type="button" wire:click="removeExperienceBullet({{ $index }}, {{ $bulletIndex }})" wire:loading.attr="disabled" class="px-2 text-ink-soft hover:text-red-600" aria-label="Remove bullet">&times;</button>
                            </div>
                        @endforeach
                    </div>

                    @if (isset($improvedBullets[$index]))
                        <div class="mt-3 rounded-sm border border-seal bg-seal-dim p-4 space-y-3">
                            <div class="flex items-center gap-2">
                                <x-seal-glyph animate class="w-5 h-5" />
                                <p class="font-mono text-xs font-medium uppercase tracking-wide text-seal">Fair copy</p>
                            </div>
                            <ul class="space-y-1 font-serif text-[15px] text-ink list-disc list-inside">
                                @foreach ($improvedBullets[$index] as $suggested)
                                    <li>{{ $suggested }}</li>
                                @endforeach
                            </ul>
                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="acceptImprovedBullets({{ $index }})" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm font-medium text-paper bg-cobalt rounded-sm hover:bg-ink transition-colors">
                                    Use these
                                </button>
                                <button type="button" wire:click="discardImprovedBullets({{ $index }})" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm font-medium text-ink-soft hover:text-ink">
                                    Discard
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if (empty($experience))
            <p class="text-sm text-ink-soft/70">No roles added yet.</p>
        @endif
    </div>
</div>
