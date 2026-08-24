@php
    $stepLabels = $this->steps();
    $totalSteps = count($stepLabels);
@endphp

<div>
    {{-- Mobile progress --}}
    <div class="sm:hidden mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="font-mono text-xs uppercase tracking-wide text-ink-soft">Step {{ $step }} of {{ $totalSteps }}</span>
            <span class="text-sm font-medium text-ink">{{ $stepLabels[$step] }}</span>
        </div>
        <div class="h-1 bg-rule rounded-full overflow-hidden">
            <div class="h-full bg-cobalt transition-all duration-300" style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
        </div>
    </div>

    {{-- Desktop stepper --}}
    <ol class="hidden sm:flex items-start mb-10">
        @foreach ($stepLabels as $num => $label)
            <li @class([
                'flex items-center',
                'flex-1' => $num < $totalSteps,
            ])>
                <button
                    type="button"
                    wire:click="goToStep({{ $num }})"
                    wire:loading.attr="disabled"
                    class="flex flex-col items-center gap-2 shrink-0 group"
                >
                    <span @class([
                        'flex items-center justify-center w-8 h-8 rounded-full text-xs font-mono font-medium shrink-0 border transition-colors',
                        'bg-cobalt border-cobalt text-paper' => $step === $num,
                        'bg-seal-dim border-seal text-seal' => $step !== $num && $this->stepIsComplete($num),
                        'bg-transparent border-rule text-ink-soft group-hover:border-ink-soft' => $step !== $num && ! $this->stepIsComplete($num),
                    ])>
                        @if ($step !== $num && $this->stepIsComplete($num))
                            &#10003;
                        @else
                            {{ $num }}
                        @endif
                    </span>
                    <span @class([
                        'text-[11px] font-medium tracking-wide text-center leading-tight',
                        'text-ink' => $step === $num,
                        'text-ink-soft' => $step !== $num,
                    ])>
                        {{ $label }}
                    </span>
                </button>

                @if ($num < $totalSteps)
                    <div @class([
                        'flex-1 h-px mx-2 mt-[-18px]',
                        'bg-seal' => $this->stepIsComplete($num) && $this->stepIsComplete($num + 1),
                        'bg-rule' => ! ($this->stepIsComplete($num) && $this->stepIsComplete($num + 1)),
                    ])></div>
                @endif
            </li>
        @endforeach
    </ol>

    <div class="bg-white border border-rule rounded-sm p-5 sm:p-8">
        @include('livewire.cv-builder.steps.' . $this->currentStepView())

        <div class="mt-8 flex items-center justify-between border-t border-rule pt-5">
            <button
                type="button"
                wire:click="previousStep"
                wire:loading.attr="disabled"
                @if ($step === 1) disabled @endif
                class="px-4 py-2 text-sm font-medium text-ink-soft rounded hover:text-ink hover:bg-paper-dim disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
            >
                Back
            </button>

            @if ($step < $totalSteps)
                <button
                    type="button"
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    class="px-5 py-2 text-sm font-medium text-paper bg-cobalt rounded hover:bg-ink transition-colors"
                >
                    Continue
                </button>
            @endif
        </div>
    </div>
</div>
