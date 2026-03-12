<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class,'index']);

Route::get('/create',[PostController::class,'create']);

Route::post('/store',[PostController::class,'store']);

Route::post('/rate/{id}',[PostController::class,'rate']);