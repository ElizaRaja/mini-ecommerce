<?php

use App\Http\Controllers\CategoriesController;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route for categories
Route::apiResource('categories', CategoriesController::class);