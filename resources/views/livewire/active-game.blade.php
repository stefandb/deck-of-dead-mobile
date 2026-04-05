<div
    x-data="swipeHandler()"
    x-on:card-completed="$wire.completeCard()"
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
    <div class="shrink-0 flex items-center justify-center py-4">
        <x-playing-card :card="$currentCard" />
    </div>

    {{-- Oefening panel --}}
    <div class="exercise-panel flex-1">
        <p class="exercise-name">{{ $currentCard->exercise->name }}</p>

        @if ($currentCard->unit === 'seconds')
            {{-- Timer (één Alpine instantie voor display + progress) --}}
            <div x-data="timer({{ $currentCard->reps }})">
                <p class="exercise-value" x-text="display">{{ $currentCard->reps }}</p>
                <p class="exercise-unit">van {{ $currentCard->reps }} sec</p>
                <div class="w-full h-1 rounded-full overflow-hidden mt-3" style="background: rgb(255 255 255 / 0.1);">
                    <div
                        class="h-full rounded-full"
                        style="background-color: var(--color-accent);"
                        x-bind:style="'width:' + pct + '%'"
                    ></div>
                </div>
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
        @endif

        <p class="swipe-hint">klaar? swipe naar volgende</p>
    </div>
</div>