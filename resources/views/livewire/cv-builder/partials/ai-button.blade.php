@php
    $target = $target ?? $action;
@endphp
<button
    type="button"
    wire:click="{{ $action }}"
    wire:loading.attr="disabled"
    wire:target="{{ $target }}"
    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-cobalt bg-cobalt-dim rounded hover:bg-cobalt hover:text-paper transition-colors disabled:opacity-60 disabled:cursor-wait"
>
    <span wire:loading.remove wire:target="{{ $target }}" class="inline-flex items-center gap-1.5">
        <x-icons.sparkle class="w-3.5 h-3.5" />
        {{ $label }}
    </span>
    <span wire:loading wire:target="{{ $target }}" class="font-mono text-xs">Thinking&hellip;</span>
</button>
