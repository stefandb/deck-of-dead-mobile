<?php

namespace App\Livewire;

use App\Models\Preset;
use App\Models\Session;
use App\Services\DeckService;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LibraryScreen extends Component
{
    #[Computed]
    public function presets(): Collection
    {
        return Preset::withCount(['rules', 'sessions'])->latest()->get();
    }

    public function startGame(int $presetId, DeckService $deckService): void
    {
        $preset = Preset::with('rules')->findOrFail($presetId);

        $session = Session::create([
            'preset_id' => $preset->id,
            'started_at' => now(),
        ]);

        $deckService->buildDeck($session, $preset);

        $this->redirect(route('game.play', $session));
    }

    public function editPreset(int $presetId): void
    {
        $this->redirect(route('config.edit', $presetId));
    }

    public function render(): View
    {
        return view('livewire.library-screen');
    }
}
