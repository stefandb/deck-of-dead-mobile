<div class="flex flex-col min-h-screen px-4 pt-6 pb-4">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a
            href="{{ route('home') }}"
            class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 shrink-0"
        >
            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-[17px] font-medium text-gray-900 dark:text-white">Start workout</h1>
    </div>

    {{-- Geselecteerde preset --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl px-4 py-3.5 mb-3 border-2"
        style="border-color: var(--color-accent);"
    >
        <p class="text-xs font-medium mb-0.5" style="color: var(--color-accent);">Geselecteerd</p>
        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $presetName }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $deckSummary }}</p>
    </div>

    {{-- Deck preview --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl px-4 py-3.5 flex-1 mb-4">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-3">Deck inhoud</p>

        @forelse ($deckPreview as $line)
            <div
                wire:key="preview-{{ $loop->index }}"
                class="flex items-center gap-3 py-2.5 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
            >
                {{-- Exercise + kaarttype --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {{ $line['exercise'] }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ $line['label'] }} · {{ $line['detail'] }}
                    </p>
                </div>

                {{-- Aantal kaarten --}}
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg px-2 py-0.5 shrink-0 tabular-nums">
                    {{ $line['count'] }}×
                </span>
            </div>
        @empty
            <div class="py-6 text-center">
                <p class="text-sm text-gray-400 dark:text-gray-500">Geen regels geconfigureerd</p>
                <a
                    href="{{ route('config.edit', request()->route('preset')) }}"
                    class="text-xs mt-1 inline-block"
                    style="color: var(--color-accent);"
                >
                    Ga naar configuratie →
                </a>
            </div>
        @endforelse
    </div>

    {{-- Knoppen --}}
    <div class="flex flex-col gap-3 mt-auto">
        {{-- Ghost: andere preset kiezen --}}
        <button
            wire:click="changePreset"
            class="w-full py-3.5 rounded-2xl text-sm font-medium border-2 transition-opacity active:opacity-70"
            style="border-color: var(--color-accent); color: var(--color-accent);"
        >
            Andere preset kiezen
        </button>

        {{-- Primary: start --}}
        <button
            wire:click="startGame"
            wire:loading.attr="disabled"
            @class([
                'w-full py-3.5 rounded-2xl text-sm font-medium text-white transition-opacity active:opacity-80',
                'opacity-40 cursor-not-allowed' => empty($deckPreview),
            ])
            style="background-color: var(--color-accent);"
            @if(empty($deckPreview)) disabled @endif
        >
            <span wire:loading.remove wire:target="startGame">Start game</span>
            <span wire:loading wire:target="startGame">Deck shufflen…</span>
        </button>
    </div>

</div>
