<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\RollController;
use Illuminate\Support\Facades\Route;


Route::post('/createRole', [RollController::class, 'store']);
Route::get('/allRoles', [RollController::class, 'getAllRoles']);

Route::post('/createUser', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/allUsers', [UserController::class, 'getAllUsers']);
Route::post('/sendEmailToRestorePassword', [UserController::class, 'sendEmailToRestorePassword']);
Route::get('/validateRecoveryToken/{token}', [UserController::class, 'validateRecoveryToken']);
Route::post('/restorePassword/{token}', [UserController::class, 'restorePassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/check-status',[UserController::class,'checkStatus']);
    Route::post('/logout', [UserController::class, 'logout']);
});
