<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;

Route::middleware('api')->group(function () {

    // Públicas (sin autenticación por ahora)
    Route::apiResource('doctors',DoctorController::class, ['only' => ['index','show']]);
    Route::apiResource('patients',PatientController::class, ['only'=>['index','show']]);

    // Protegidas por rol
    Route::middleware('role:doctor,admin')->group(function(){
        Route::post('/appointments',[AppointmentController::class, 'store']);
        Route::put('appointments/{id}',[AppointmentController::class, 'update']);
    });

    Route::middleware('role:admin')->group(function(){
        Route::delete('/appointments/{id}',[AppointmentController::class,'destroy']);
    });

    Route::apiResource('appointments',AppointmentController::class, ['only'=>['index','show']]);

    // Route::apiResource('doctors', DoctorController::class);
    // Route::apiResource('patients', PatientController::class);
    // Route::apiResource('appointments', AppointmentController::class);
});
