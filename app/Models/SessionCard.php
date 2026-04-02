<?php

namespace App\Models;

use Database\Factories\SessionCardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionCard extends Model
{
    /** @use HasFactory<SessionCardFactory> */
    use HasFactory;

    protected $fillable = [
        'session_id', 'position', 'suit', 'value',
        'exercise_id', 'unit', 'reps', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function isCompleted(): bool
    {
        return ! is_null($this->completed_at);
    }

    /**
     * Geeft de weergavetekst voor de eenheid.
     * Bijv. "7 herhalingen", "800 meter", "60 seconden"
     */
    public function displayLabel(): string
    {
        return match ($this->unit) {
            'reps' => $this->reps.' '.($this->reps === 1 ? 'herhaling' : 'herhalingen'),
            'meters' => $this->reps.' meter',
            'seconds' => $this->reps.' seconden',
        };
    }

    public function suitColor(): string
    {
        return in_array($this->suit, ['hearts', 'diamonds']) ? 'red' : 'black';
    }

    public function suitSymbol(): string
    {
        return match ($this->suit) {
            'hearts' => '♥',
            'diamonds' => '♦',
            'clubs' => '♣',
            'spades' => '♠',
            default => '★',
        };
    }
}
