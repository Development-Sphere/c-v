@props(['withWordmark' => true])

<span {{ $attributes->class(['inline-flex items-center gap-2.5']) }}>
    <x-seal-glyph class="w-7 h-7" />

    @if ($withWordmark)
        <span class="font-serif text-lg tracking-tight text-ink">{{ config('app.name', 'Fair Copy') }}</span>
    @endif
</span>
