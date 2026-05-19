<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Main routes
Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/store', [PostController::class, 'store'])->name('posts.store');
Route::post('/rate/{id}', [PostController::class, 'rate'])->name('posts.rate');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('posts.destroy');