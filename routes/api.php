<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::apiResource('products', ProductController::class);
Route::get('categories/{id}/products', [CategoryController::class, 'showProducts']);
