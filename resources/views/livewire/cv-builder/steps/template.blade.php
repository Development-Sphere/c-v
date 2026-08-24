<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Choose a template</h3>
        <p class="text-sm text-gray-500">You can switch templates any time without losing your content.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach ($templates as $cvTemplate)
            <button
                type="button"
                wire:key="template-{{ $cvTemplate->key }}"
                wire:click="selectTemplate('{{ $cvTemplate->key }}')"
                wire:loading.attr="disabled"
                @class([
                    'text-left border-2 rounded-lg p-4 transition',
                    'border-indigo-600 ring-2 ring-indigo-100' => $template === $cvTemplate->key,
                    'border-gray-200 hover:border-gray-300' => $template !== $cvTemplate->key,
                ])
            >
                <p class="font-medium text-gray-900">{{ $cvTemplate->name }}</p>
                @if ($cvTemplate->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $cvTemplate->description }}</p>
                @endif
                @if ($template === $cvTemplate->key)
                    <p class="mt-2 text-xs font-medium text-indigo-600">Selected</p>
                @endif
            </button>
        @endforeach
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700 mb-2">Live preview</p>
        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-100" style="height: 70vh;">
            <iframe
                src="{{ route('cv.preview', $cv) }}?v={{ $previewVersion }}"
                title="CV preview"
                class="w-full h-full"
            ></iframe>
        </div>
    </div>
</div>
