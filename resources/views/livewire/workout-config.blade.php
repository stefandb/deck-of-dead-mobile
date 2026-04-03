<div>
<div class="flex flex-col min-h-screen pb-4">

    {{-- Header --}}
    <div class="flex items-center gap-3 px-4 pt-6 pb-4">
        <a href="{{ route('home') }}" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 shrink-0">
            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-[17px] font-medium text-gray-900 dark:text-white">
            {{ $presetId ? 'Bewerk workout' : 'Nieuwe workout' }}
        </h1>
    </div>

    {{-- Preset naam --}}
    <div class="px-4 mb-4">
        <input
            type="text"
            wire:model="presetName"
            placeholder="Naam van deze workout…"
            class="w-full bg-white dark:bg-gray-800 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 border border-transparent focus:border-accent focus:outline-none"
        >
        @error('presetName')
            <p class="text-xs mt-1" style="color: var(--color-suit-red);">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tab switcher --}}
    <div class="px-4 mb-4">
        <div class="flex bg-gray-100 dark:bg-gray-800 rounded-xl p-1 gap-1">
            @foreach (['numbers' => 'Nummers', 'faces' => 'Plaatjes', 'specials' => 'Specials'] as $tab => $label)
                <button
                    wire:click="$set('activeTab', '{{ $tab }}')"
                    @class([
                        'flex-1 py-1.5 text-xs font-medium rounded-lg transition-colors',
                        'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' => $activeTab === $tab,
                        'text-gray-500 dark:text-gray-400' => $activeTab !== $tab,
                    ])
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Tab content --}}
    <div class="flex-1 px-4">

        {{-- === Tab: Nummers === --}}
        @if ($activeTab === 'numbers')
            {{-- Toggle per suite / rood-zwart --}}
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 mb-3">
                <span class="text-xs text-gray-500 dark:text-gray-400 flex-1">Per suite</span>
                <button
                    type="button"
                    wire:click="$toggle('numbersByColor')"
                    class="relative w-10 h-6 rounded-full transition-colors shrink-0"
                    :class="$wire.numbersByColor ? 'bg-accent' : 'bg-gray-300 dark:bg-gray-600'"
                    style="{{ $numbersByColor ? 'background-color:var(--color-accent)' : '' }}"
                >
                    <span
                        class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-all"
                        :class="$wire.numbersByColor ? 'left-5' : 'left-1'"
                        style="left: {{ $numbersByColor ? '1.25rem' : '0.25rem' }}"
                    ></span>
                </button>
                <span class="text-xs font-medium text-gray-900 dark:text-white shrink-0">Rood / Zwart</span>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden">
                @foreach ($numberGroups as $group)
                    <div
                        wire:key="number-{{ $group['key'] }}"
                        class="flex items-center gap-2 px-4 py-3.5 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                    >
                        <span class="text-lg w-7 text-center font-medium shrink-0"
                              style="color: {{ $group['isRed'] ? 'var(--color-suit-red)' : 'var(--color-suit-black)' }}">
                            {{ $group['symbol'] }}
                        </span>

                        <button
                            class="badge-exercise truncate max-w-[120px]"
                            wire:click="openExerciseSheet('{{ $group['key'] }}')"
                        >
                            {{ $exerciseNames[$group['key']] ?? 'Kies oefening' }}
                        </button>

                        @if (isset($rules[$group['key']]))
                            <span class="badge-unit shrink-0">
                                {{ $rules[$group['key']]['unit'] ?? 'reps' }}
                            </span>
                            <span class="text-xs text-gray-400 ml-auto shrink-0">
                                × {{ $rules[$group['key']]['value'] }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400 ml-auto shrink-0">× kaart</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- === Tab: Plaatjes === --}}
        @if ($activeTab === 'faces')
            {{-- Toggle per type / rood-zwart --}}
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 mb-3">
                <span class="text-xs text-gray-500 dark:text-gray-400 flex-1">Per type</span>
                <button
                    type="button"
                    wire:click="$toggle('faceCardByColor')"
                    class="relative w-10 h-6 rounded-full transition-colors shrink-0"
                    style="{{ $faceCardByColor ? 'background-color:var(--color-accent)' : 'background-color:#d1d5db' }}"
                >
                    <span
                        class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-all"
                        style="left: {{ $faceCardByColor ? '1.25rem' : '0.25rem' }}"
                    ></span>
                </button>
                <span class="text-xs font-medium text-gray-900 dark:text-white shrink-0">Rood / Zwart</span>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden">
                @foreach ($faceGroups as $group)
                    <div
                        wire:key="face-{{ $group['key'] }}"
                        class="flex items-center gap-2 px-4 py-3.5 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                    >
                        <span class="text-sm font-semibold w-7 text-center shrink-0"
                              style="color: {{ $group['isRed'] === true ? 'var(--color-suit-red)' : ($group['isRed'] === false ? 'var(--color-suit-black)' : 'var(--color-accent)') }}">
                            {{ $group['symbol'] }}
                        </span>

                        <button
                            class="badge-exercise truncate max-w-[100px]"
                            wire:click="openExerciseSheet('{{ $group['key'] }}')"
                        >
                            {{ $exerciseNames[$group['key']] ?? 'Kies oefening' }}
                        </button>

                        @if (isset($rules[$group['key']]))
                            <span class="badge-unit shrink-0">
                                {{ $rules[$group['key']]['unit'] ?? 'reps' }}
                            </span>
                        @endif

                        <div class="flex items-center gap-1 ml-auto shrink-0">
                            <button
                                wire:click="decrementValue('{{ $group['key'] }}')"
                                class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium"
                            >−</button>
                            <span class="text-sm font-medium text-gray-900 dark:text-white w-8 text-center tabular-nums">
                                {{ $rules[$group['key']]['value'] ?? 10 }}
                            </span>
                            <button
                                wire:click="incrementValue('{{ $group['key'] }}')"
                                class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium"
                            >+</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- === Tab: Specials === --}}
        @if ($activeTab === 'specials')
            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden">
                @foreach ([['key' => 'ace_any', 'symbol' => 'A', 'label' => 'Aas', 'count' => '4×'], ['key' => 'joker_any', 'symbol' => '★', 'label' => 'Joker', 'count' => '2×']] as $special)
                    <div
                        wire:key="special-{{ $special['key'] }}"
                        class="flex items-center gap-2 px-4 py-3.5 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                    >
                        <span class="text-sm font-bold w-7 text-center shrink-0" style="color: var(--color-accent)">
                            {{ $special['symbol'] }}
                        </span>
                        <span class="text-xs text-gray-400 shrink-0">{{ $special['count'] }}</span>

                        <button
                            class="badge-exercise truncate max-w-[100px]"
                            wire:click="openExerciseSheet('{{ $special['key'] }}')"
                        >
                            {{ $exerciseNames[$special['key']] ?? 'Kies oefening' }}
                        </button>

                        @if (isset($rules[$special['key']]))
                            <span class="badge-unit shrink-0">
                                {{ $rules[$special['key']]['unit'] ?? 'reps' }}
                            </span>
                        @endif

                        <div class="flex items-center gap-1 ml-auto shrink-0">
                            <button
                                wire:click="decrementValue('{{ $special['key'] }}')"
                                class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium"
                            >−</button>
                            <span class="text-sm font-medium text-gray-900 dark:text-white w-8 text-center tabular-nums">
                                {{ $rules[$special['key']]['value'] ?? 60 }}
                            </span>
                            <button
                                wire:click="incrementValue('{{ $special['key'] }}')"
                                class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium"
                            >+</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 px-1">
                Aas heeft altijd een vaste waarde (geen × kaart). Joker ook.
            </p>
        @endif

    </div>

    {{-- Opslaan --}}
    <div class="px-4 mt-6">
        <button
            wire:click="savePreset"
            wire:loading.attr="disabled"
            class="w-full py-3.5 rounded-2xl text-sm font-medium text-white transition-opacity active:opacity-80 disabled:opacity-50"
            style="background-color: var(--color-accent);"
        >
            <span wire:loading.remove>Opslaan als preset</span>
            <span wire:loading>Opslaan…</span>
        </button>
    </div>

</div>

{{-- ===================== Exercise bottom sheet ===================== --}}
<div
    x-data
    x-show="$wire.exerciseSheetOpen"
    class="fixed inset-0 z-50 flex flex-col justify-end"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-black/40"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        wire:click="$set('exerciseSheetOpen', false)"
    ></div>

    {{-- Sheet --}}
    <div
        class="relative bg-white dark:bg-gray-900 rounded-t-2xl max-h-[78vh] flex flex-col"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
    >
        {{-- Drag handle --}}
        <div class="w-10 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mt-3 mb-1 shrink-0"></div>

        <div class="px-4 pt-2 pb-3 shrink-0">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Kies oefening</h3>

            {{-- Zoekbalk --}}
            <input
                type="text"
                wire:model.live.debounce.200ms="exerciseSearch"
                placeholder="Zoeken of nieuw invoeren…"
                class="w-full bg-gray-100 dark:bg-gray-800 rounded-xl px-3.5 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none"
            >
        </div>

        {{-- Oefening lijst --}}
        <div class="overflow-y-auto flex-1 px-4">
            @foreach ($filteredExercises as $exercise)
                <button
                    wire:key="exercise-{{ $exercise->id }}"
                    wire:click="selectExercise({{ $exercise->id }})"
                    @class([
                        'w-full text-left px-3 py-2.5 rounded-xl text-sm transition-colors mb-0.5',
                        'font-medium text-white' => $selectedExerciseId === $exercise->id,
                        'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' => $selectedExerciseId !== $exercise->id,
                    ])
                    style="{{ $selectedExerciseId === $exercise->id ? 'background-color:var(--color-accent)' : '' }}"
                >
                    {{ $exercise->name }}
                </button>
            @endforeach

            @if ($exerciseSearch && $filteredExercises->isEmpty())
                <button
                    wire:click="createAndSelectExercise"
                    class="w-full text-left px-3 py-2.5 rounded-xl text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                    + "{{ $exerciseSearch }}" toevoegen
                </button>
            @endif
        </div>

        {{-- Eenheid en waarde --}}
        <div class="px-4 pt-3 pb-2 border-t border-gray-100 dark:border-gray-800 shrink-0">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Eenheid</p>

            <div class="flex bg-gray-100 dark:bg-gray-800 rounded-xl p-1 gap-1 mb-3">
                @foreach (['reps' => 'Herhalingen', 'meters' => 'Meters', 'seconds' => 'Seconden'] as $unit => $unitLabel)
                    <button
                        wire:click="$set('selectedUnit', '{{ $unit }}')"
                        @class([
                            'flex-1 py-1.5 text-xs font-medium rounded-lg transition-colors',
                            'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' => $selectedUnit === $unit,
                            'text-gray-500 dark:text-gray-400' => $selectedUnit !== $unit,
                        ])
                    >
                        {{ $unitLabel }}
                    </button>
                @endforeach
            </div>

            {{-- Waarde stepper --}}
            <div class="flex items-center gap-3 mb-3">
                <p class="text-xs text-gray-500 dark:text-gray-400 flex-1">
                    @if ($sheetIsNumberType)
                        Waarde per kaartnummer
                    @else
                        Vaste waarde
                    @endif
                </p>
                <div class="flex items-center gap-2">
                    <button
                        wire:click="decrementBase"
                        class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 font-medium"
                    >−</button>
                    <span class="text-sm font-medium text-gray-900 dark:text-white w-10 text-center tabular-nums">
                        {{ $baseValue }}
                    </span>
                    <button
                        wire:click="incrementBase"
                        class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 font-medium"
                    >+</button>
                </div>
            </div>

            {{-- Bevestig --}}
            <button
                wire:click="confirmExercise"
                class="w-full py-3 rounded-2xl text-sm font-medium text-white"
                style="background-color: var(--color-accent);"
            >
                Kies
            </button>
        </div>

    </div>
</div>
</div>
