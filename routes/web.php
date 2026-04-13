<?php

use App\Http\Controllers\HomeController;
use App\Livewire\ActiveGame;
use App\Livewire\GameStart;
use App\Livewire\LibraryScreen;
use App\Livewire\WorkoutComplete;
use App\Livewire\WorkoutConfig;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/config', WorkoutConfig::class)->name('config.create');
Route::get('/config/{preset}', WorkoutConfig::class)->name('config.edit');

Route::get('/library', LibraryScreen::class)->name('library');
Route::get('/history', fn () => abort(404))->name('history');
Route::get('/game/start/{preset?}', GameStart::class)->name('game.start');
Route::get('/game/{session}', ActiveGame::class)->name('game.play');
Route::get('/game/{session}/complete', WorkoutComplete::class)->name('game.complete');
