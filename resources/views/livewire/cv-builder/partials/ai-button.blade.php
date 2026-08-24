@php
    $target = $target ?? $action;
@endphp
<button
    type="button"
    wire:click="{{ $action }}"
    wire:loading.attr="disabled"
    wire:target="{{ $target }}"
    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50 disabled:opacity-50"
>
    <span wire:loading.remove wire:target="{{ $target }}">{{ $label }}</span>
    <span wire:loading wire:target="{{ $target }}">Thinking&hellip;</span>
</button>
