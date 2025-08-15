<?php

use App\Http\Controllers\MemoryController;
use App\Http\Controllers\WordsController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

Route::get('/', function () {
    return view('home');
});

Route::prefix('jogos')->group(function () {

    Route::get('/memoria', [MemoryController::class, 'ranking'])->name('memory');
    Route::post('/memoria', [MemoryController::class, 'store'])->name('store.memory');

    Route::get('/forca', [WordsController::class, 'ranking'])->name('words');
    Route::post('/forca', [WordsController::class, 'store'])->name('store.words');
});
