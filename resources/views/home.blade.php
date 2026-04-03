@extends('layouts.app')

@section('content')
    <div class="flex flex-col min-h-full px-4 pt-6 pb-4">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-[17px] font-medium text-gray-900 dark:text-white">Dead of Deck</h1>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Kies een workout of maak een nieuwe aan</p>
        </div>

        {{-- Preset lijst --}}
        <div class="flex flex-col gap-3 flex-1">
            @forelse ($presets as $preset)
                <a
                    href="{{ route('game.start', $preset) }}"
                    class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl px-4 py-3.5 shadow-sm active:scale-[0.98] transition-transform"
                >
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
                            {{ $preset->rules()->count() }} regels &middot; 54 kaarten
                        </p>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @empty
                <div class="flex-1 flex flex-col items-center justify-center text-center py-16">
                    <div
                        class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-4"
                        style="background-color: var(--color-accent-light);"
                    >
                        🃏
                    </div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Nog geen workouts</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Maak je eerste preset aan om te starten</p>
                </div>
            @endforelse
        </div>

        {{-- Nieuwe workout knop --}}
        <div class="pt-6">
            <a
                href="{{ route('config.create') }}"
                class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-sm font-medium text-white transition-opacity active:opacity-80"
                style="background-color: var(--color-accent);"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nieuwe workout
            </a>
        </div>

    </div>
@endsection
