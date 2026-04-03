<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Placeholders — worden later geïmplementeerd
Route::get('/library', fn () => view('layouts.app'))->name('library');
Route::get('/history', fn () => view('layouts.app'))->name('history');

Route::get('/config', fn () => abort(404))->name('config.create');
Route::get('/config/{preset}', fn () => abort(404))->name('config.edit');
Route::get('/game/start/{preset?}', fn () => abort(404))->name('game.start');
Route::get('/game/{session}', fn () => abort(404))->name('game.play');
Route::get('/game/{session}/complete', fn () => abort(404))->name('game.complete');
