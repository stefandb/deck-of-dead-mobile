<?php

namespace App\Livewire;

use App\Enums\FaceCardMode;
use App\Models\Preset;
use App\Models\PresetRule;
use App\Models\Session;
use App\Services\DeckService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class GameStart extends Component
{
    public ?int $presetId = null;

    public function mount(?Preset $preset = null): void
    {
        if (! $preset?->exists) {
            $this->redirect(route('home'));

            return;
        }

        $this->presetId = $preset->id;
    }

    #[Computed]
    public function preset(): Preset
    {
        return Preset::with('rules.exercise')->findOrFail($this->presetId);
    }

    #[Computed]
    public function deckPreview(): array
    {
        $typeOrder = ['number' => 0, 'jack' => 1, 'queen' => 2, 'king' => 3, 'ace' => 4, 'joker' => 5];

        return $this->preset->rules
            ->sortBy(fn (PresetRule $rule) => $typeOrder[$rule->card_type] ?? 9)
            ->map(fn (PresetRule $rule) => [
                'label' => $this->cardLabel($rule),
                'exercise' => $rule->exercise->name,
                'detail' => $this->valueDetail($rule),
                'count' => $this->cardCount($rule),
            ])
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values()
            ->toArray();
    }

    #[Computed]
    public function deckSummary(): string
    {
        $total = array_sum(array_column($this->deckPreview, 'count'));
        $mode = $this->preset->face_card_mode === FaceCardMode::ByColor
            ? 'rood/zwart modus'
            : 'type modus';

        return "{$total} kaarten · {$mode}";
    }

    private function cardCount(PresetRule $rule): int
    {
        return match (true) {
            $rule->card_type === 'number' && in_array($rule->suit, ['hearts', 'diamonds', 'clubs', 'spades']) => 9,
            $rule->card_type === 'number' && in_array($rule->suit, ['red', 'black']) => 18,
            in_array($rule->card_type, ['jack', 'queen', 'king']) && $rule->suit === 'any' => 4,
            in_array($rule->card_type, ['jack', 'queen', 'king']) && in_array($rule->suit, ['red', 'black']) => 2,
            in_array($rule->card_type, ['jack', 'queen', 'king']) && in_array($rule->suit, ['hearts', 'diamonds', 'clubs', 'spades']) => 1,
            $rule->card_type === 'ace' => 4,
            $rule->card_type === 'joker' => 2,
            default => 0,
        };
    }

    private function cardLabel(PresetRule $rule): string
    {
        $suitSymbol = match ($rule->suit) {
            'hearts' => '♥',
            'diamonds' => '♦',
            'clubs' => '♣',
            'spades' => '♠',
            'red' => '♥♦',
            'black' => '♠♣',
            default => '',
        };

        $typeLabel = match ($rule->card_type) {
            'number' => 'Nummers 2–10',
            'jack' => 'Boer J',
            'queen' => 'Vrouw Q',
            'king' => 'Heer K',
            'ace' => 'Aas A',
            'joker' => 'Joker ★',
            default => ucfirst($rule->card_type),
        };

        return $suitSymbol ? "{$typeLabel} {$suitSymbol}" : $typeLabel;
    }

    private function valueDetail(PresetRule $rule): string
    {
        $unitLabel = match ($rule->unit) {
            'meters' => 'm',
            'seconds' => 'sec',
            default => 'reps',
        };

        return $rule->card_type === 'number'
            ? '×'.$rule->value.' '.$unitLabel
            : $rule->value.' '.$unitLabel;
    }

    public function startGame(DeckService $deckService): void
    {
        if (empty($this->deckPreview)) {
            return;
        }

        $preset = Preset::with('rules')->findOrFail($this->presetId);

        $session = Session::create([
            'preset_id' => $preset->id,
            'started_at' => now(),
        ]);

        $deckService->buildDeck($session, $preset);

        $this->redirect(route('game.play', $session));
    }

    public function changePreset(): void
    {
        $this->redirect(route('home'));
    }

    public function render(): View
    {
        return view('livewire.game-start', [
            'presetName' => $this->preset->name,
            'deckSummary' => $this->deckSummary,
            'deckPreview' => $this->deckPreview,
        ]);
    }
}
