<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Patient;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     * Listamos lo mínimo para no saturar la respuesta
     */
    public function index()
    {
        $patients = Patient::all();
        return response()->json([
            'success' => true,
            'data' => $patients,
        ]);
    }

    /**
     * Display the specified resource.
     * Traemos más contexto por tratarse de un registro.
     */
    public function show(string $id):JsonResponse
    {
        $patient = Patient::with('appointments','medicalRecords')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $patient,
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
