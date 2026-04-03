<?php

namespace App\Livewire;

use App\Enums\FaceCardMode;
use App\Models\Exercise;
use App\Models\Preset;
use App\Models\PresetRule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WorkoutConfig extends Component
{
    public ?int $presetId = null;

    public string $presetName = '';

    public string $activeTab = 'numbers';

    public bool $numbersByColor = false;

    public bool $faceCardByColor = false;

    /** @var array<string, array{exercise_id: int|null, unit: string, value: int}> */
    public array $rules = [];

    // Exercise bottom sheet state
    public bool $exerciseSheetOpen = false;

    public string $exerciseSearch = '';

    public ?string $sheetKey = null;

    public ?int $selectedExerciseId = null;

    public string $selectedUnit = 'reps';

    public int $baseValue = 1;

    public function mount(?Preset $preset = null): void
    {
        if (! $preset?->exists) {
            return;
        }

        $this->presetId = $preset->id;
        $this->presetName = $preset->name;
        $this->faceCardByColor = $preset->face_card_mode === FaceCardMode::ByColor;

        $preset->rules()->get()->each(function (PresetRule $rule) {
            $this->rules[$this->ruleKey($rule->card_type, $rule->suit)] = [
                'exercise_id' => $rule->exercise_id,
                'unit' => $rule->unit,
                'value' => $rule->value,
            ];
        });
    }

    private function ruleKey(string $cardType, string $suit): string
    {
        // Merge jack/queen/king + red/black into face_red / face_black when loading
        if (in_array($cardType, ['jack', 'queen', 'king']) && in_array($suit, ['red', 'black'])) {
            return 'face_'.$suit;
        }

        return $cardType.'_'.$suit;
    }

    #[Computed]
    public function numberGroups(): array
    {
        if ($this->numbersByColor) {
            return [
                ['key' => 'number_red', 'symbol' => '♥♦', 'label' => 'Rood', 'isRed' => true],
                ['key' => 'number_black', 'symbol' => '♠♣', 'label' => 'Zwart', 'isRed' => false],
            ];
        }

        return [
            ['key' => 'number_hearts', 'symbol' => '♥', 'label' => 'Harten', 'isRed' => true],
            ['key' => 'number_diamonds', 'symbol' => '♦', 'label' => 'Ruiten', 'isRed' => true],
            ['key' => 'number_clubs', 'symbol' => '♣', 'label' => 'Klaveren', 'isRed' => false],
            ['key' => 'number_spades', 'symbol' => '♠', 'label' => 'Schoppen', 'isRed' => false],
        ];
    }

    #[Computed]
    public function faceGroups(): array
    {
        if ($this->faceCardByColor) {
            return [
                ['key' => 'face_red', 'symbol' => '♥♦', 'label' => 'Rood (J/Q/K)', 'isRed' => true],
                ['key' => 'face_black', 'symbol' => '♠♣', 'label' => 'Zwart (J/Q/K)', 'isRed' => false],
            ];
        }

        return [
            ['key' => 'jack_any', 'symbol' => 'J', 'label' => 'Boer', 'isRed' => null],
            ['key' => 'queen_any', 'symbol' => 'Q', 'label' => 'Vrouw', 'isRed' => null],
            ['key' => 'king_any', 'symbol' => 'K', 'label' => 'Heer', 'isRed' => null],
        ];
    }

    #[Computed]
    public function filteredExercises()
    {
        return Exercise::query()
            ->when(
                $this->exerciseSearch,
                fn ($q) => $q->where('name', 'like', '%'.$this->exerciseSearch.'%')
            )
            ->orderBy('name')
            ->get();
    }

    /** @return array<string, string|null> */
    #[Computed]
    public function exerciseNames(): array
    {
        $ids = collect($this->rules)->pluck('exercise_id')->filter()->unique();

        if ($ids->isEmpty()) {
            return [];
        }

        $exercises = Exercise::whereIn('id', $ids)->pluck('name', 'id');

        return collect($this->rules)
            ->mapWithKeys(fn ($rule, $key) => [
                $key => $exercises[$rule['exercise_id'] ?? null] ?? null,
            ])
            ->all();
    }

    #[Computed]
    public function sheetIsNumberType(): bool
    {
        return $this->sheetKey !== null && str_starts_with($this->sheetKey, 'number_');
    }

    public function updatedNumbersByColor(): void
    {
        foreach (array_keys($this->rules) as $key) {
            if (str_starts_with($key, 'number_')) {
                unset($this->rules[$key]);
            }
        }
    }

    public function updatedFaceCardByColor(): void
    {
        foreach (array_keys($this->rules) as $key) {
            $type = explode('_', $key)[0];
            if (in_array($type, ['jack', 'queen', 'king', 'face'])) {
                unset($this->rules[$key]);
            }
        }
    }

    public function openExerciseSheet(string $key): void
    {
        $this->sheetKey = $key;
        $this->exerciseSearch = '';

        $rule = $this->rules[$key] ?? null;
        $this->selectedExerciseId = $rule['exercise_id'] ?? null;
        $this->selectedUnit = $rule['unit'] ?? 'reps';
        $this->baseValue = $rule['value'] ?? 1;

        $this->exerciseSheetOpen = true;
    }

    public function selectExercise(int $id): void
    {
        $this->selectedExerciseId = $id;
    }

    public function confirmExercise(): void
    {
        if (! $this->sheetKey || ! $this->selectedExerciseId) {
            $this->exerciseSheetOpen = false;

            return;
        }

        $this->rules[$this->sheetKey] = [
            'exercise_id' => $this->selectedExerciseId,
            'unit' => $this->selectedUnit,
            'value' => $this->baseValue,
        ];

        $this->exerciseSheetOpen = false;
        $this->sheetKey = null;
    }

    public function createAndSelectExercise(): void
    {
        if (! $this->exerciseSearch) {
            return;
        }

        $exercise = Exercise::firstOrCreate(['name' => trim($this->exerciseSearch)]);
        $this->selectedExerciseId = $exercise->id;
        $this->exerciseSearch = '';
    }

    public function incrementValue(string $key): void
    {
        $base = array_merge(['exercise_id' => null, 'unit' => 'reps', 'value' => 1], $this->rules[$key] ?? []);
        $this->rules[$key] = array_merge($base, ['value' => $base['value'] + 1]);
    }

    public function decrementValue(string $key): void
    {
        $base = array_merge(['exercise_id' => null, 'unit' => 'reps', 'value' => 2], $this->rules[$key] ?? []);
        $this->rules[$key] = array_merge($base, ['value' => max(1, $base['value'] - 1)]);
    }

    public function incrementBase(): void
    {
        $this->baseValue++;
    }

    public function decrementBase(): void
    {
        $this->baseValue = max(1, $this->baseValue - 1);
    }

    public function savePreset(): void
    {
        $this->validate(['presetName' => ['required', 'string', 'max:255']]);

        $preset = $this->presetId
            ? Preset::findOrFail($this->presetId)
            : new Preset;

        $preset->fill([
            'name' => $this->presetName,
            'face_card_mode' => $this->faceCardByColor ? FaceCardMode::ByColor : FaceCardMode::ByType,
        ]);
        $preset->save();

        $preset->rules()->delete();

        foreach ($this->rules as $key => $ruleData) {
            if (! ($ruleData['exercise_id'] ?? null)) {
                continue;
            }

            [$cardType, $suit] = explode('_', $key, 2);

            if ($cardType === 'face') {
                // face_red / face_black → expand to jack, queen, king each
                foreach (['jack', 'queen', 'king'] as $faceType) {
                    $preset->rules()->create([
                        'card_type' => $faceType,
                        'suit' => $suit,
                        'exercise_id' => $ruleData['exercise_id'],
                        'unit' => $ruleData['unit'],
                        'value' => $ruleData['value'],
                    ]);
                }
            } else {
                $preset->rules()->create([
                    'card_type' => $cardType,
                    'suit' => $suit,
                    'exercise_id' => $ruleData['exercise_id'],
                    'unit' => $ruleData['unit'],
                    'value' => $ruleData['value'],
                ]);
            }
        }

        $this->redirect(route('home'));
    }

    public function render(): View
    {
        return view('livewire.workout-config', [
            'numberGroups' => $this->numberGroups,
            'faceGroups' => $this->faceGroups,
            'filteredExercises' => $this->filteredExercises,
            'exerciseNames' => $this->exerciseNames,
            'sheetIsNumberType' => $this->sheetIsNumberType,
        ]);
    }
}
