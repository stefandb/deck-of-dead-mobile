<?php

namespace App\Models;

use App\Enums\FaceCardMode;
use Database\Factories\PresetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Preset extends Model
{
    /** @use HasFactory<PresetFactory> */
    use HasFactory;

    protected $fillable = ['name', 'face_card_mode'];

    protected function casts(): array
    {
        return [
            'face_card_mode' => FaceCardMode::class,
        ];
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PresetRule::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Zoek de regel voor een specifieke kaart.
     * Houdt rekening met face_card_mode (by_type vs by_color).
     */
    public function ruleFor(string $cardValue, string $suit): ?PresetRule
    {
        $cardType = $this->resolveCardType($cardValue);
        $suitKey = $this->resolveSuit($cardType, $suit);

        return $this->rules()
            ->where('card_type', $cardType)
            ->where('suit', $suitKey)
            ->first();
    }

    private function resolveCardType(string $value): string
    {
        return match (true) {
            is_numeric($value) => 'number',
            $value === 'J' => 'jack',
            $value === 'Q' => 'queen',
            $value === 'K' => 'king',
            $value === 'A' => 'ace',
            default => 'joker',
        };
    }

    private function resolveSuit(string $cardType, string $suit): string
    {
        // Joker and ace always use 'any' — stored as a single rule regardless of suit
        if (in_array($cardType, ['joker', 'ace'])) {
            return 'any';
        }

        // Face cards by_color: group hearts/diamonds → red, clubs/spades → black
        if (in_array($cardType, ['jack', 'queen', 'king']) && $this->face_card_mode === FaceCardMode::ByColor) {
            return in_array($suit, ['hearts', 'diamonds']) ? 'red' : 'black';
        }

        // Face cards by_type: stored with suit='any' (one rule per type covers all suits)
        if (in_array($cardType, ['jack', 'queen', 'king'])) {
            return 'any';
        }

        // Numbers: prefer suit-specific rule, fall back to red/black color rule
        if ($cardType === 'number') {
            $hasSpecificRule = $this->rules()
                ->where('card_type', 'number')
                ->where('suit', $suit)
                ->exists();

            if ($hasSpecificRule) {
                return $suit;
            }

            return in_array($suit, ['hearts', 'diamonds']) ? 'red' : 'black';
        }

        return $suit;
    }
}
