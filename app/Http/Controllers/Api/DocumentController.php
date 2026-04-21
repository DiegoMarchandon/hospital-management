<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function upload(Request $request, $medicalRecordId): JsonResponse
    {
        $request->validate([
            'document' => 'required|file|max:10240', //10MB Max
        ]);

        $medicalRecord = MedicalRecord::findOrFail($medicalRecordId);

        // Guardar en storage/app/medical_documents/
        $path = $request->file('document')->store('medical_documents');

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded',
            'path' => $path,
        ]);
    }

    public function download($medicalRecordId): JsonResponse
    {
        $medicalRecord = MedicalRecord::findOrFail($medicalRecordId);

        // En producción, aquí iría a S3
        $documents = Storage::files('medical_documents');

        return response()->json([
            'success' => true,
            'documents' => $documents,
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
     * Display the specified resource.
     */
    public function show(string $id)
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
