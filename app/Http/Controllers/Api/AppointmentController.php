<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listamos lo mínimo para no saturar la respuesta
     */
    public function index()
    {
        $appointments = Appointment::with('doctor','patient','schedule')->get();
        return response()->json([
            'success' => true,
            'data' => $appointments,
        ]);
    }

    /**
     * Display the specified resource.
     * Traemos más contexto por tratarse de un registro.
     */
    public function show(string $id):JsonResponse
    {
        $appointment = Appointment::with('doctor','patient','schedule')->findOrFail($id);
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
