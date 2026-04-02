<?php

namespace App\Models;

use Database\Factories\PresetRuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresetRule extends Model
{
    /** @use HasFactory<PresetRuleFactory> */
    use HasFactory;

    protected $fillable = [
        'preset_id', 'exercise_id', 'card_type', 'suit', 'unit', 'value',
    ];

    public function preset(): BelongsTo
    {
        return $this->belongsTo(Preset::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Bereken de weergegeven waarde voor een specifieke kaart.
     * Voor nummers: kaartnummer × value. Voor plaatjes/specials: value direct.
     */
    public function calculateFor(int|string $cardNumber): int
    {
        if ($this->card_type === 'number') {
            return (int) $cardNumber * $this->value;
        }

        return $this->value;
    }
}
