<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $exercises = [
            'Push-up',
            'Squat',
            'Burpee',
            'Dips',
            'Lunges',
            'Plank',
            'Wall sit',
            'Roeien (erg)',
            'Skiën (erg)',
            'Loopband',
            'Pull-up',
            'Mountain climber',
        ];

        foreach ($exercises as $name) {
            Exercise::firstOrCreate(['name' => $name]);
        }
    }
}
