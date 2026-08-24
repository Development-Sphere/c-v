<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-gray-900">Your CVs</h3>
                    <p class="text-sm text-gray-500">Pick up where you left off, or start a new one.</p>
                </div>

                <form method="POST" action="{{ route('cv.store') }}">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                    >
                        + New CV
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @forelse ($cvs as $cv)
                    <a
                        href="{{ route('cv.edit', $cv) }}"
                        wire:navigate
                        class="flex items-center justify-between p-6 hover:bg-gray-50 {{ ! $loop->last ? 'border-b border-gray-100' : '' }}"
                    >
                        <div>
                            <p class="font-medium text-gray-900">{{ $cv->title }}</p>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($cv->template) }} template &middot;
                                {{ $cv->status->value === 'complete' ? 'Complete' : 'Draft' }} &middot;
                                Updated {{ $cv->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <span class="text-gray-400">&rarr;</span>
                    </a>
                @empty
                    <div class="p-6 text-gray-500 text-sm">
                        {{ __("You haven't started a CV yet.") }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
