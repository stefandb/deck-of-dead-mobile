<?php

namespace App\Models;

use Database\Factories\SessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    /** @use HasFactory<SessionFactory> */
    use HasFactory;

    protected $table = 'game_sessions';

    protected $fillable = ['preset_id', 'started_at', 'finished_at', 'duration_seconds'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function preset(): BelongsTo
    {
        return $this->belongsTo(Preset::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(SessionCard::class)->orderBy('position');
    }

    public function completedCards(): HasMany
    {
        return $this->hasMany(SessionCard::class)->whereNotNull('completed_at')->orderBy('position');
    }

    public function currentCard(): ?SessionCard
    {
        return $this->cards()->whereNull('completed_at')->first();
    }

    public function isFinished(): bool
    {
        return ! is_null($this->finished_at);
    }
}
