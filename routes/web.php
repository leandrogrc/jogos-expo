<?php

use App\Http\Controllers\MemoryController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

Route::get('/', function () {
    return view('home');
});

Route::prefix('jogos')->group(function () {

    Route::get('/memoria', [MemoryController::class, 'ranking'])->name('memoria');
    Route::post('/memoria', [MemoryController::class, 'store'])->name('store.score');

    Route::get('/forca', function () {
        return view('games.forca');
    })->name('forca');
});
