<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Doctor;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listamos lo mínimo para no saturar la respuesta
     */
    public function index(): JsonResponse
    {
        $doctors = Doctor::with('specialty')->get();
        return response()->json([
            'success' => true,
            'data' => $doctors,
        ]);
    }

    /**
     * Display the specified resource.
     * Traemos más contexto por tratarse de un registro.
     */
    public function show(string $id):JsonResponse
    {
        $doctor = Doctor::with('specialty','appointments')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $doctor,
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
