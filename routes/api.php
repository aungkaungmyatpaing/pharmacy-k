<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\MedicineAttributeController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Medicine Dictionary APIs
Route::get('/medicines', [MedicineController::class, 'index']);
Route::get('/medicines/{id}', [MedicineController::class, 'show']);

// Medicine Attributes APIs
Route::get('/categories', [MedicineAttributeController::class, 'categories']);
Route::get('/brands', [MedicineAttributeController::class, 'brands']);
Route::get('/effects', [MedicineAttributeController::class, 'effects']);
Route::get('/side-effects', [MedicineAttributeController::class, 'sideEffects']);
