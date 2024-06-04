<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\RollController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\EjercicioController;
use App\Http\Controllers\RutinaController;
use App\Http\Controllers\UnidadesMedidaController;
use Illuminate\Support\Facades\Route;


//RollController
Route::controller(RollController::class)->group(function () {
    Route::post('/createRole', 'store');
    Route::get('/allRoles', 'getAllRoles');
    Route::get('/usersByRole/{rol_id}', 'getUsersByRole');
});

//UserController
Route::controller(UserController::class)->group(function () {
    Route::post('/createUser', 'store');
    Route::post('/login', 'login')->name('login');
    Route::post('/sendEmailToRestorePassword', 'sendEmailToRestorePassword');
    Route::get('/validateRecoveryToken/{token}', 'validateRecoveryToken');
    Route::post('/restorePassword/{token}', 'restorePassword');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/check-status', 'checkStatus');
        Route::post('/logout', 'logout');
        Route::get('/getDetailInscription', 'getDetailInscription');
        Route::get('/allUsers', 'getAllUsers');
        Route::delete('/deleteUsuario/{id_usuario}', 'deleteUsuario');
        Route::patch('/changePassword', 'changePassword');
        Route::patch('/changeInfoProfile/{id_usuario}', 'changeInfoProfile');
    });
});

//InscripcionController
Route::controller(InscripcionController::class)->group(function () {
    Route::get('/allInscriptions', 'getAllInscriptions');
    Route::get('/getInscriptionById/{id_inscripcion}', 'getInscriptionById');
    Route::post('/generateInscription', 'store');
    Route::put('/updateInscription/{id_inscripcion}', 'updateInscription');
    Route::delete('/deleteInscription/{id_inscripcion}', 'deleteInscription');
    Route::get('/getActiveInscription', 'getActiveInscription');
});

//Ejercicios
Route::controller(EjercicioController::class)->group(function () {
    //Tipos de ejercicio
    Route::post('/createTipoEjercicio', 'storeTipoEjercicio');
    Route::get('/getAllTipoEjercicio', 'getAllTipoEjercicio');
    Route::put('/updateTypeTrining/{id_tipoEjercicio}', 'updateTipoEjercicio');
    Route::delete('/deleteTypeTrining/{id_tipoEjercicio}', 'deleteTipoEjericio');
    //Ejercicios
    Route::post('/createEjercicio', 'storeEjercicio');
    Route::get('/getAllEjercicios', 'getAllEjercicios');
    Route::get('/getInfoBasicEjercicios', 'getInfoBasicEjercicios');
    Route::put('/updateEjercicio/{id_ejercicio}', 'updateEjercicio');
    Route::delete('/deleteEjercicio/{id_ejercicio}', 'deleteEjercicio');
});

Route::controller(RutinaController::class)->group(function (){
    Route::post('/createRutina', 'storeWood');
    Route::get('/showRutinas', 'showRutinas');
    Route::get('/getRutina/{id_rutina}', 'getRutina');
    Route::delete('/deleteRutina/{id_rutina}', 'deleteRutina');
    Route::put('/updateRutina/{id_rutina}', 'updateRutina');
    Route::post('/saveResult', 'storeResult');
    Route::get('/getResultRoutine/{id_rutina}', 'getResultRoutine');
});

//Unidades de medida
Route::controller(UnidadesMedidaController::class)->group(function (){
    Route::post('/createUnidadMedida', 'store');
    Route::get('/allUnidadesMedida', 'getAll');
    Route::put('/updateUnidadMedida/{id_unidadMedida}', 'update');
    Route::delete('/deleteUnidadMedida/{id_unidadMedida}', 'destroy');
});