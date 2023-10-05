<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Controllers
use App\Http\Controllers\Auth\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Login
Route::post('/registerClient', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);