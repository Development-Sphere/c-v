<div class="space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Skills</h3>
        <p class="text-sm text-gray-500">Add the skills most relevant to the role you're applying for.</p>
    </div>

    <form wire:submit.prevent="addSkill" class="flex gap-2">
        <label for="new-skill" class="sr-only">Add a skill</label>
        <input
            id="new-skill"
            type="text"
            wire:model="newSkill"
            placeholder="e.g. Laravel"
            class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
        />
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
        >
            Add
        </button>
    </form>

    <div class="flex flex-wrap gap-2">
        @foreach ($skills as $index => $skill)
            <span wire:key="skill-{{ $index }}" class="inline-flex items-center gap-1 pl-3 pr-1 py-1 rounded-full bg-indigo-50 text-indigo-700 text-sm">
                {{ $skill }}
                <button
                    type="button"
                    wire:click="removeSkill({{ $index }})"
                    wire:loading.attr="disabled"
                    class="w-5 h-5 flex items-center justify-center rounded-full hover:bg-indigo-100"
                    aria-label="Remove {{ $skill }}"
                >
                    &times;
                </button>
            </span>
        @endforeach

        @if (empty($skills))
            <p class="text-sm text-gray-400">No skills added yet.</p>
        @endif
    </div>

    <div>
        @include('livewire.cv-builder.partials.ai-button', [
            'action' => 'suggestSkills',
            'label' => '✨ Suggest skills',
        ])
        @include('livewire.cv-builder.partials.ai-error')
    </div>

    @if (count($suggestedSkills) > 0)
        <div class="rounded-lg border border-indigo-200 bg-indigo-50 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Suggestions &mdash; click to add</p>
                <button type="button" wire:click="dismissSuggestedSkills" wire:loading.attr="disabled" class="text-xs text-gray-500 hover:text-gray-700">
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
                        class="inline-flex items-center gap-1 pl-3 pr-2 py-1 rounded-full bg-white border border-indigo-300 text-indigo-700 text-sm hover:bg-indigo-100"
                    >
                        + {{ $suggested }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</div>
