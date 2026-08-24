@props(['animate' => false])

<svg viewBox="0 0 40 40" {{ $attributes->class(['shrink-0', 'motion-safe:animate-stamp' => $animate]) }} aria-hidden="true">
    <circle cx="20" cy="20" r="19" fill="#C89B3C" />
    <g stroke="#FAF9F4" stroke-width="1.4" stroke-linecap="round">
        <line x1="20" y1="9" x2="20" y2="13" />
        <line x1="20" y1="27" x2="20" y2="31" />
        <line x1="9" y1="20" x2="13" y2="20" />
        <line x1="27" y1="20" x2="31" y2="20" />
        <line x1="12.5" y1="12.5" x2="15.2" y2="15.2" />
        <line x1="24.8" y1="24.8" x2="27.5" y2="27.5" />
        <line x1="27.5" y1="12.5" x2="24.8" y2="15.2" />
        <line x1="15.2" y1="24.8" x2="12.5" y2="27.5" />
    </g>
    <circle cx="20" cy="20" r="5.5" fill="#FAF9F4" />
</svg>
