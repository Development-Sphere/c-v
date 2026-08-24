<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-xl text-ink leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-rule sm:rounded-sm p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-ink">Your CVs</h3>
                    <p class="text-sm text-ink-soft">Pick up where you left off, or start a new one.</p>
                </div>

                <form method="POST" action="{{ route('cv.store') }}">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-paper bg-cobalt rounded-sm hover:bg-ink transition-colors"
                    >
                        + New CV
                    </button>
                </form>
            </div>

            <div class="bg-white border border-rule sm:rounded-sm">
                @forelse ($cvs as $cv)
                    <a
                        href="{{ route('cv.edit', $cv) }}"
                        wire:navigate
                        class="flex items-center justify-between p-6 hover:bg-paper-dim {{ ! $loop->last ? 'border-b border-rule' : '' }}"
                    >
                        <div>
                            <p class="font-medium text-ink">{{ $cv->title }}</p>
                            <p class="text-sm text-ink-soft">
                                {{ ucfirst($cv->template) }} template &middot;
                                {{ $cv->status->value === 'complete' ? 'Complete' : 'Draft' }} &middot;
                                Updated {{ $cv->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <span class="text-ink-soft">&rarr;</span>
                    </a>
                @empty
                    <div class="p-6 text-ink-soft text-sm">
                        {{ __("You haven't started a CV yet.") }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
