<?php

use App\Http\Controllers\HomeController;
use App\Livewire\GameStart;
use App\Livewire\WorkoutConfig;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/config', WorkoutConfig::class)->name('config.create');
Route::get('/config/{preset}', WorkoutConfig::class)->name('config.edit');

// Placeholders — worden later geïmplementeerd
Route::get('/library', fn () => abort(404))->name('library');
Route::get('/history', fn () => abort(404))->name('history');
Route::get('/game/start/{preset?}', GameStart::class)->name('game.start');
Route::get('/game/{session}', fn () => abort(404))->name('game.play');
Route::get('/game/{session}/complete', fn () => abort(404))->name('game.complete');
