@php
    $stepLabels = $this->steps();
    $totalSteps = count($stepLabels);
@endphp

<div>
    {{-- Mobile progress --}}
    <div class="sm:hidden mb-4">
        <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
            <span>Step {{ $step }} of {{ $totalSteps }}</span>
            <span class="font-medium text-gray-800">{{ $stepLabels[$step] }}</span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-indigo-600 transition-all" style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
        </div>
    </div>

    {{-- Desktop stepper --}}
    <ol class="hidden sm:flex items-center mb-8">
        @foreach ($stepLabels as $num => $label)
            <li @class([
                'flex items-center',
                'flex-1' => $num < $totalSteps,
            ])>
                <button
                    type="button"
                    wire:click="goToStep({{ $num }})"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 shrink-0 group"
                >
                    <span @class([
                        'flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold shrink-0 transition',
                        'bg-indigo-600 text-white' => $step === $num,
                        'bg-green-100 text-green-700' => $step !== $num && $this->stepIsComplete($num),
                        'bg-gray-200 text-gray-500' => $step !== $num && ! $this->stepIsComplete($num),
                    ])>
                        @if ($step !== $num && $this->stepIsComplete($num))
                            &check;
                        @else
                            {{ $num }}
                        @endif
                    </span>
                    <span @class([
                        'text-xs font-medium group-hover:text-gray-800',
                        'text-gray-900' => $step === $num,
                        'text-gray-500' => $step !== $num,
                    ])>
                        {{ $label }}
                    </span>
                </button>

                @if ($num < $totalSteps)
                    <div class="flex-1 h-px bg-gray-300 mx-2"></div>
                @endif
            </li>
        @endforeach
    </ol>

    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
        @include('livewire.cv-builder.steps.' . $this->currentStepView())

        <div class="mt-6 flex items-center justify-between border-t pt-4">
            <button
                type="button"
                wire:click="previousStep"
                wire:loading.attr="disabled"
                @if ($step === 1) disabled @endif
                class="px-4 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed"
            >
                Back
            </button>

            @if ($step < $totalSteps)
                <button
                    type="button"
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                >
                    Next
                </button>
            @endif
        </div>
    </div>
</div>
