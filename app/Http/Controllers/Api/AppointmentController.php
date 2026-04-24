<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Appointment;
use Illuminate\Support\Facades\Cache;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listamos lo mínimo para no saturar la respuesta
     */
    public function index()
    {
        // Cachear por 1 hora
        $appointments = Cache::remember('appointments.all', 3600, function(){
            return Appointment::with('doctor','patient','schedule')->get();
        });
        // $appointments = Appointment::with('doctor','patient','schedule')->get();
        return response()->json([
            'success' => true,
            'data' => $appointments,
        ]);
    }

    /**
     * Display the specified resource.
     * Traemos más contexto por tratarse de un registro.
     */
    public function show($id):JsonResponse
    {
        $appointment = Cache::remember("appointment.{$id}", 3600, function() use ($id){
            return Appointment::with('doctor','patient','schedule')->findOrFail($id);
        });
        // $appointment = Appointment::with('doctor','patient','schedule')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'=> $appointment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'appointment_time' => 'required|date_format:H:i',
                'reason' => 'required|string|max:255',
            ]);

            $patient = \App\Models\Patient::where('email', auth()->user()->email)->first();

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient profile not found',
                ], 404);
            }

            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $validated['doctor_id'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);

            Cache::forget('appointments.all');

            return response()->json([
                'success' => true,
                'data' => $appointment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => get_class($e)
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $appointment->update($validated);

        Cache::forget('appointments.all');
        Cache::forget("appointment.{$id}");

        return response()->json([
            'success' => true,
            'data' => $appointment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
