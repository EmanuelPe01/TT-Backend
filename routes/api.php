<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Controllers
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\RolController;

//Middleware
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Usuarios
Route::post('/registerClient', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/allUsers', [UserController::class, 'getAllUsers']);

//Roles
Route::post('/createRole', [RolController::class, 'store']);
Route::get('/allRoles', [RolController::class, 'getAllRoles']);