<div
    x-data
    x-init="
        import('https://cdn.jsdelivr.net/npm/canvas-confetti@1/dist/confetti.browser.min.js')
            .then(m => m.default({ particleCount: 150, spread: 80, origin: { y: 0.5 } }))
    "
    class="complete-screen"
>
    {{-- Emoji + titel --}}
    <div class="complete-hero">
        <p class="complete-emoji">🎉</p>
        <h2 class="complete-title">Workout klaar!</h2>
        <p class="complete-sub">{{ $session->cards()->count() }} kaarten voltooid</p>
    </div>

    {{-- Stats grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <p class="stat-value">{{ $this->duration }}</p>
            <p class="stat-label">Tijd</p>
        </div>
        <div class="stat-card">
            <p class="stat-value">{{ $this->totalReps }}</p>
            <p class="stat-label">Totaal reps</p>
        </div>
    </div>

    {{-- Per oefening --}}
    <div class="w-full">
        <p class="text-xs font-medium mb-3" style="color: rgb(255 255 255 / 0.4);">Per oefening</p>
        @foreach($this->exerciseSummary as $item)
            <div class="flex justify-between text-sm py-1.5 border-b last:border-b-0" style="border-color: rgb(255 255 255 / 0.07);">
                <span class="text-white/80">{{ $item['name'] }}</span>
                <span style="color: rgb(255 255 255 / 0.45);">
                    {{ $item['total'] }}
                    @if($item['unit'] === 'meters') m
                    @elseif($item['unit'] === 'seconds') sec
                    @else reps
                    @endif
                </span>
            </div>
        @endforeach
</div>

    {{-- Acties --}}
    <div class="w-full flex flex-col gap-3 mt-auto pb-safe">
        <button variant="primary" wire:click="goHome" class="w-full">
            Terug naar home
        </button>
    </div>
</div>
