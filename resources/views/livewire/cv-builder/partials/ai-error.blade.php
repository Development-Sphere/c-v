@if ($aiError)
    <div class="mt-2 flex items-center justify-between gap-2 rounded-md bg-red-50 px-3 py-2 text-sm text-red-700">
        <span>{{ $aiError }}</span>
        <button type="button" wire:click="$set('aiError', null)" class="text-red-400 hover:text-red-600" aria-label="Dismiss">&times;</button>
    </div>
@endif
