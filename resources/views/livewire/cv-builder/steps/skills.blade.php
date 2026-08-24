<div class="space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-ink">Skills</h3>
        <p class="text-sm text-ink-soft">Add the skills most relevant to the role you're applying for.</p>
    </div>

    <form wire:submit.prevent="addSkill" class="flex gap-2">
        <label for="new-skill" class="sr-only">Add a skill</label>
        <input
            id="new-skill"
            type="text"
            wire:model="newSkill"
            placeholder="e.g. Laravel"
            class="flex-1 rounded-sm border-rule text-ink shadow-sm text-sm focus:border-cobalt focus:ring-cobalt"
        />
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="px-4 py-2 text-sm font-medium text-paper bg-cobalt rounded-sm hover:bg-ink transition-colors"
        >
            Add
        </button>
    </form>

    <div class="flex flex-wrap gap-2">
        @foreach ($skills as $index => $skill)
            <span wire:key="skill-{{ $index }}" class="inline-flex items-center gap-1 pl-3 pr-1 py-1 rounded-full bg-cobalt-dim text-cobalt text-sm">
                {{ $skill }}
                <button
                    type="button"
                    wire:click="removeSkill({{ $index }})"
                    wire:loading.attr="disabled"
                    class="w-5 h-5 flex items-center justify-center rounded-full hover:bg-cobalt/10"
                    aria-label="Remove {{ $skill }}"
                >
                    &times;
                </button>
            </span>
        @endforeach

        @if (empty($skills))
            <p class="text-sm text-ink-soft/70">No skills added yet.</p>
        @endif
    </div>

    <div>
        @include('livewire.cv-builder.partials.ai-button', [
            'action' => 'suggestSkills',
            'label' => 'Suggest skills',
        ])
        @include('livewire.cv-builder.partials.ai-error')
    </div>

    @if (count($suggestedSkills) > 0)
        <div class="rounded-sm border border-seal bg-seal-dim p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-seal-glyph animate class="w-5 h-5" />
                    <p class="font-mono text-xs font-medium uppercase tracking-wide text-seal">Suggestions — click to add</p>
                </div>
                <button type="button" wire:click="dismissSuggestedSkills" wire:loading.attr="disabled" class="text-xs text-ink-soft hover:text-ink">
                    Dismiss
                </button>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($suggestedSkills as $suggested)
                    <button
                        type="button"
                        wire:key="suggested-skill-{{ $suggested }}"
                        wire:click="acceptSuggestedSkill(@js($suggested))"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1 pl-3 pr-2 py-1 rounded-full bg-white border border-seal text-ink text-sm hover:bg-seal hover:text-white transition-colors"
                    >
                        + {{ $suggested }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</div>
