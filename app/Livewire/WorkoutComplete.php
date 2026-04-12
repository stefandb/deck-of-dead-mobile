<?php

namespace App\Livewire;

use App\Models\Session;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class WorkoutComplete extends Component
{
    public Session $session;

    public function mount(Session $session): void
    {
        if (! $session->isFinished()) {
            $this->redirect(route('game.play', $session));
        }

        $this->session = $session;
    }

    #[Computed]
    public function duration(): string
    {
        $seconds = $this->session->duration_seconds ?? 0;
        $minutes = intdiv($seconds, 60);
        $remaining = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $remaining);
    }

    #[Computed]
    public function totalReps(): int
    {
        return $this->session->cards()->sum('reps');
    }

    #[Computed]
    public function exerciseSummary(): array
    {
        return $this->session->cards()
            ->with('exercise')
            ->get()
            ->groupBy('exercise_id')
            ->map(fn ($cards) => [
                'name' => $cards->first()->exercise->name,
                'total' => $cards->sum('reps'),
                'unit' => $cards->first()->unit,
            ])
            ->values()
            ->toArray();
    }

    public function goHome(): void
    {
        $this->redirect(route('home'));
    }

    public function render(): View
    {
        return view('livewire.workout-complete');
    }
}
