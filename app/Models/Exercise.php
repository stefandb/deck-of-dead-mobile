<?php

namespace App\Models;

use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function presetRules(): HasMany
    {
        return $this->hasMany(PresetRule::class);
    }

    public function sessionCards(): HasMany
    {
        return $this->hasMany(SessionCard::class);
    }
}
