<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
//posts
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
