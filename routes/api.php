<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;

Route::middleware('api')->group(function () {

    // Públicas (sin autenticación por ahora)
    Route::apiResource('doctors',DoctorController::class, ['only' => ['index','show']]);
    Route::apiResource('patients',PatientController::class, ['only'=>['index','show']]);

    // Appointment listing accessible to all authenticated users
    Route::apiResource('appointments',AppointmentController::class, ['only'=>['index','show']]);

    // Protegidas por rol - Patients can create appointments
    Route::middleware('role:patient')->group(function(){
        Route::post('/appointments',[AppointmentController::class, 'store']);
    });

    // Protegidas por rol - Doctors can update appointments
    Route::middleware('role:doctor,admin')->group(function(){
        Route::put('appointments/{id}',[AppointmentController::class, 'update']);
        Route::post('/medical-records/{id}/upload',[DocumentController::class, 'upload']);
        Route::get('/medical-records/{id}/documents',[DocumentController::class,'download']);
    });

    Route::middleware('role:admin')->group(function(){
        Route::delete('/appointments/{id}',[AppointmentController::class,'destroy']);
    });

    // API Documentation - Swagger UI
    Route::get('/docs', function () {
        return view('api.docs');
    })->name('api.docs');
    
    Route::get('/api-spec.json', function () {
        return response()->json([
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Hospital Management System API',
                'version' => '1.0.0',
                'description' => 'RESTful API for Hospital Management System with role-based access control',
                'contact' => ['email' => 'support@hospital.com'],
                'license' => ['name' => 'MIT', 'url' => 'https://opensource.org/licenses/MIT']
            ],
            'servers' => [
                ['url' => env('APP_URL') . '/api', 'description' => 'API Server']
            ],
            'paths' => [
                '/doctors' => [
                    'get' => [
                        'tags' => ['Doctors'],
                        'summary' => 'Get list of all doctors',
                        'parameters' => [
                            ['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer']],
                            ['name' => 'limit', 'in' => 'query', 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'List of doctors'],
                            '401' => ['description' => 'Unauthorized']
                        ]
                    ]
                ],
                '/patients' => [
                    'get' => [
                        'tags' => ['Patients'],
                        'summary' => 'Get list of all patients',
                        'responses' => [
                            '200' => ['description' => 'List of patients'],
                            '401' => ['description' => 'Unauthorized']
                        ]
                    ]
                ],
                '/appointments' => [
                    'get' => [
                        'tags' => ['Appointments'],
                        'summary' => 'Get appointments (filtered by role)',
                        'responses' => [
                            '200' => ['description' => 'List of appointments'],
                            '401' => ['description' => 'Unauthorized']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Appointments'],
                        'summary' => 'Create new appointment',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'properties' => [
                                            'doctor_id' => ['type' => 'integer'],
                                            'appointment_date' => ['type' => 'string', 'format' => 'date'],
                                            'appointment_time' => ['type' => 'string'],
                                            'reason' => ['type' => 'string']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => ['description' => 'Appointment created'],
                            '422' => ['description' => 'Validation error'],
                            '401' => ['description' => 'Unauthorized']
                        ]
                    ]
                ],
                '/appointments/{id}' => [
                    'put' => [
                        'tags' => ['Appointments'],
                        'summary' => 'Update appointment status',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Appointment updated'],
                            '403' => ['description' => 'Forbidden'],
                            '404' => ['description' => 'Not found']
                        ]
                    ],
                    'delete' => [
                        'tags' => ['Appointments'],
                        'summary' => 'Cancel appointment',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Appointment cancelled'],
                            '403' => ['description' => 'Forbidden'],
                            '404' => ['description' => 'Not found']
                        ]
                    ]
                ]
            ]
        ]);
    })->name('api.spec');
});
