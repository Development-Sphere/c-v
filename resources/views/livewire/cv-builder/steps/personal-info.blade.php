<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Personal info</h3>
        <p class="text-sm text-gray-500">This appears at the top of your CV.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="pi-name" class="block text-sm font-medium text-gray-700">Full name</label>
            <input
                id="pi-name"
                type="text"
                wire:model.live.debounce.750ms="personalInfo.name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label for="pi-email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
                id="pi-email"
                type="email"
                wire:model.live.debounce.750ms="personalInfo.email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
            @error('personalInfo.email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="pi-phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input
                id="pi-phone"
                type="text"
                wire:model.live.debounce.750ms="personalInfo.phone"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label for="pi-location" class="block text-sm font-medium text-gray-700">Location</label>
            <input
                id="pi-location"
                type="text"
                wire:model.live.debounce.750ms="personalInfo.location"
                placeholder="City, Country"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between">
            <label class="block text-sm font-medium text-gray-700">Links</label>
            <button type="button" wire:click="addLink" wire:loading.attr="disabled" class="text-sm text-indigo-600 hover:text-indigo-800">
                + Add link
            </button>
        </div>

        <div class="mt-2 space-y-2">
            @foreach ($personalInfo['links'] ?? [] as $index => $link)
                <div wire:key="link-{{ $index }}" class="flex gap-2">
                    <input
                        type="text"
                        wire:model.live.debounce.750ms="personalInfo.links.{{ $index }}.label"
                        placeholder="Label (e.g. LinkedIn)"
                        class="block w-1/3 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <input
                        type="url"
                        wire:model.live.debounce.750ms="personalInfo.links.{{ $index }}.url"
                        placeholder="https://..."
                        class="block flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <button
                        type="button"
                        wire:click="removeLink({{ $index }})"
                        wire:loading.attr="disabled"
                        class="px-2 text-gray-400 hover:text-red-600"
                        aria-label="Remove link"
                    >
                        &times;
                    </button>
                </div>
            @endforeach

            @if (empty($personalInfo['links']))
                <p class="text-sm text-gray-400">No links added yet.</p>
            @endif
        </div>
    </div>
</div>
