<div class="flex flex-col min-h-full px-4 pt-6 pb-4">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-[17px] font-medium text-gray-900 dark:text-white">Library</h1>
        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Al je opgeslagen workouts</p>
    </div>

    {{-- Preset lijst --}}
    <div class="flex flex-col gap-3 flex-1">
        @forelse($this->presets as $preset)
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl px-4 py-3.5 shadow-sm">
                {{-- Suit icon --}}
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center text-xl shrink-0"
                    style="background-color: var(--color-accent-light);"
                >
                    <span style="color: var(--color-accent);">♠</span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $preset->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $preset->rules_count }} regels &middot; {{ $preset->sessions_count }} keer gespeeld
                    </p>
                </div>

                {{-- Acties --}}
                <div class="flex gap-2 shrink-0">
                    <button
                        wire:click="startGame({{ $preset->id }})"
                        class="text-xs font-medium px-3 py-1.5 rounded-xl text-white transition-opacity active:opacity-75"
                        style="background-color: var(--color-accent);"
                    >
                        Start
                    </button>
                    <button
                        wire:click="editPreset({{ $preset->id }})"
                        class="text-xs font-medium px-3 py-1.5 rounded-xl border transition-opacity active:opacity-75 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600"
                    >
                        Bewerk
                    </button>
                </div>
            </div>
        @empty
            <div class="flex-1 flex flex-col items-center justify-center text-center py-16">
                <div
                    class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-4"
                    style="background-color: var(--color-accent-light);"
                >
                    🃏
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Nog geen presets</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Maak er een aan op de home pagina</p>
            </div>
        @endforelse
    </div>

</div>
