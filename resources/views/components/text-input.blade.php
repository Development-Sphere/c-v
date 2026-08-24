@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-rule text-ink focus:border-cobalt focus:ring-cobalt rounded-sm shadow-sm']) }}>
