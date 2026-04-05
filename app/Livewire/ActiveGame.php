<?php

namespace App\Livewire;

use App\Models\Session;
use App\Models\SessionCard;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class ActiveGame extends Component
{
    public Session $session;

    public ?int $currentCardId = null;

    public function mount(Session $session): void
    {
        if ($session->isFinished()) {
            $this->redirect(route('game.complete', $session));

            return;
        }

        $this->session = $session;

        $card = $session->currentCard();

        if (! $card) {
            $this->session->update([
                'finished_at' => now(),
                'duration_seconds' => now()->diffInSeconds($this->session->started_at),
            ]);
            $this->redirect(route('game.complete', $session));

            return;
        }

        $this->currentCardId = $card->id;
    }

    #[Computed]
    public function currentCard(): ?SessionCard
    {
        return $this->currentCardId
            ? SessionCard::with('exercise')->find($this->currentCardId)
            : null;
    }

    public function completeCard(): void
    {
        SessionCard::find($this->currentCardId)?->update(['completed_at' => now()]);
        unset($this->currentCard);

        $next = $this->session->currentCard();

        if (! $next) {
            $this->session->update([
                'finished_at' => now(),
                'duration_seconds' => now()->diffInSeconds($this->session->started_at),
            ]);
            $this->redirect(route('game.complete', $this->session));

            return;
        }

        $this->currentCardId = $next->id;
    }

    #[On('card-completed')]
    public function handleNewPost($refreshPosts = false)
    {
        dd(1234);
    }

    public function render(): View
    {
        $totalCards = $this->session->cards()->count();
        $completedCards = $this->session->cards()->whereNotNull('completed_at')->count();

        return view('livewire.active-game', [
            'currentCard' => $this->currentCard,
            'totalCards' => $totalCards,
            'completedCards' => $completedCards,
        ]);
    }
}
