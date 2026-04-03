<?php

namespace App\Services;

use App\Models\Preset;
use App\Models\Session;

class DeckService
{
    /**
     * Genereer en shuffle een volledig deck voor een preset.
     * Sla de kaarten op als session_cards.
     */
    public function buildDeck(Session $session, Preset $preset): void
    {
        $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        $cards = [];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $rule = $preset->ruleFor($value, $suit);

                if (! $rule) {
                    continue;
                }

                $numericValue = is_numeric($value) ? (int) $value : null;
                $calculated = $rule->calculateFor($numericValue ?? 1);

                $cards[] = [
                    'suit' => $suit,
                    'value' => $value,
                    'exercise_id' => $rule->exercise_id,
                    'unit' => $rule->unit,
                    'reps' => $calculated,
                ];
            }
        }

        $jokerRule = $preset->ruleFor('Joker', 'any');

        if ($jokerRule) {
            $joker = [
                'suit' => 'joker',
                'value' => 'Joker',
                'exercise_id' => $jokerRule->exercise_id,
                'unit' => $jokerRule->unit,
                'reps' => $jokerRule->value,
            ];

            $cards[] = $joker;
            $cards[] = $joker;
        }

        shuffle($cards);

        foreach ($cards as $index => $card) {
            $session->cards()->create(array_merge($card, ['position' => $index + 1]));
        }
    }
}
