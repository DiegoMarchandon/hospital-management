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
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
