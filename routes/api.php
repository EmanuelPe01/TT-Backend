<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\RollController;
use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;

//RollController
Route::post('/createRole', [RollController::class, 'store']);
Route::get('/allRoles', [RollController::class, 'getAllRoles']);
Route::get('/usersByRole/{rol_id}', [RollController::class, 'getUsersByRole']);

//UserController
Route::post('/createUser', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/allUsers', [UserController::class, 'getAllUsers']);
Route::post('/sendEmailToRestorePassword', [UserController::class, 'sendEmailToRestorePassword']);
Route::get('/validateRecoveryToken/{token}', [UserController::class, 'validateRecoveryToken']);
Route::post('/restorePassword/{token}', [UserController::class, 'restorePassword']);

//InscripcionController
Route::get('/allInscriptions', [InscripcionController::class, 'getAllInscriptions']);
Route::get('/getInscriptionById/{id_inscripcion}', [InscripcionController::class, 'getInscriptionById']);
Route::post('/generateInscription', [InscripcionController::class, 'store']);
Route::put('/updateInscription/{id_inscripcion}', [InscripcionController::class, 'updateInscription']);
Route::delete('/deleteInscription/{id_inscripcion}', [InscripcionController::class, 'deleteInscription']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/check-status',[UserController::class,'checkStatus']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/getDetailInscription', [UserController::class, 'getDetailInscription']);
});
