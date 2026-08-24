<div class="space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Professional summary</h3>
        <p class="text-sm text-gray-500">A short paragraph introducing yourself. Two or three sentences is plenty.</p>
    </div>

    <div>
        <label for="summary-raw" class="sr-only">Summary</label>
        <textarea
            id="summary-raw"
            rows="6"
            wire:model.live.debounce.750ms="summary.raw"
            placeholder="e.g. Backend engineer with 6 years of experience building payments infrastructure..."
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        ></textarea>
    </div>
</div>
