<?php

use App\Http\Controllers\Recipe\DestroyController;
use App\Http\Controllers\Recipe\IndexController;
use App\Http\Controllers\Recipe\ShowController;
use App\Http\Controllers\Recipe\StoreController;
use App\Http\Controllers\Recipe\UpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('recipes', StoreController::class)
        ->name('api.recipes.store');
    Route::put(('recipes/{recipe}'), UpdateController::class)
        ->name('api.recipes.update')
        ->where('recipe', '[0-9]+');
    Route::delete('recipes/{recipe}', DestroyController::class)
        ->name('api.recipes.destroy')
        ->where('recipe', '[0-9]+');
});

Route::get('recipes', IndexController::class)->name('api.recipes.index');
Route::get('recipes/{recipe}', ShowController::class)
    ->name('api.recipes.show')
    ->where('recipe', '[0-9]+');
