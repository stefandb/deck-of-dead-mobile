<div
    x-data="swipeHandler()"
    x-on:card-completed="$wire.completeCard()"
    x-on:timer-lock.window="swipeLocked = true"
    x-on:timer-unlock.window="swipeLocked = false"
    class="game-screen"
>
    {{-- Progress header --}}
    <div class="px-5 pt-5 pb-3 shrink-0">
        <div class="flex items-center gap-3">
            <p class="text-xs tabular-nums whitespace-nowrap shrink-0" style="color: rgb(255 255 255 / 0.5);">
                Kaart {{ $completedCards }} van {{ $totalCards }}
            </p>

            {{-- Progress bar --}}
            <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background: rgb(255 255 255 / 0.1);">
                <div
                    class="h-full rounded-full transition-all duration-300"
                    style="width: {{ $totalCards > 0 ? round(($completedCards / $totalCards) * 100) : 0 }}%; background-color: var(--color-accent);"
                ></div>
            </div>

            <p class="text-xs tabular-nums whitespace-nowrap shrink-0" style="color: rgb(255 255 255 / 0.5);">
                {{ $totalCards > 0 ? round(($completedCards / $totalCards) * 100) : 0 }}%
            </p>
        </div>
    </div>

    {{-- Speelkaart gecentreerd --}}
    <div class="shrink-0 flex items-center justify-center py-4" data-swipe-zone>
        <x-playing-card :card="$currentCard" />
    </div>

    {{-- Oefening panel --}}
    <div class="exercise-panel flex-1">
        <p class="exercise-name">{{ $currentCard->exercise->name }}</p>

        @if ($currentCard->unit === 'seconds')
            <div wire:key="{{ $currentCard->id }}-timer" x-data="timer({{ $currentCard->reps }})" class="flex flex-col items-center gap-4 flex-1">
                {{-- Cirkel timer --}}
                <div class="relative">
                    <svg width="180" height="180" viewBox="0 0 200 200" class="-rotate-90">
                        <circle cx="100" cy="100" r="80" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="10"/>
                        <circle
                            cx="100" cy="100" r="80" fill="none"
                            stroke-width="10"
                            stroke-linecap="round"
                            :stroke="color"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="dashoffset"
                            style="transition: stroke-dashoffset 1s linear, stroke 1s linear;"
                        />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-5xl font-bold text-white tabular-nums" x-text="display">{{ $currentCard->reps }}</span>
                    </div>
                </div>

                {{-- Start knop --}}
                <button
                    x-show="!running && !finished"
                    x-on:click="start()"
                    class="timer-start-btn"
                >
                    Start
                </button>

                <p class="swipe-hint mt-auto pb-2" x-show="finished">klaar? swipe naar volgende</p>
            </div>
        @else
            {{-- Reps of meters --}}
            <p class="exercise-value">{{ $currentCard->reps }}</p>
            <p class="exercise-unit">
                @if ($currentCard->unit === 'meters')
                    meter
                @else
                    {{ $currentCard->reps === 1 ? 'herhaling' : 'herhalingen' }}
                @endif
            </p>
            <p class="swipe-hint">klaar? swipe naar volgende</p>
        @endif
    </div>
</div>