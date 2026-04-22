<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{

/* --FALSOS POSITIVOS-- 
Los undefined methods son falsos positivos porque Spatie Permissions agrega 
métodos en tiempo de ejecución (dinámicamente). Y Laravel Facades como url() y 
route() también se resuelven dinámicamente. 
El IDE estático no puede seguirle el flujo.

-- */
    /**
     * Show upload form for patient
     */
    public function showUploadForm()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('patient')) {
            abort(403, 'Only patients can upload documents');
        }

        return view('documents.upload');
    }

    /**
     * Upload document for medical record
     */
    public function upload(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('patient')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|string|max:255',
        ]);

        // Get patient model
        $patient = \App\Models\Patient::where('email', $user->email)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient profile not found'], 404);
        }

        try {
            // Store in S3
            $path = $request->file('document')->store('medical_documents', 's3');
            
            // Get the file URL
            $url = Storage::disk('s3')->url($path);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'path' => $path,
                'url' => $url,
                'description' => $request->input('description', ''),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download document
     */
    public function download($path)
    {
        $user = Auth::user();

        try {
            if (!Storage::disk('s3')->exists($path)) {
                abort(404, 'Document not found');
            }

            // Check permissions
            if ($user->hasRole('patient')) {
                $patient = $user->patient;
                if (!$patient) {
                    abort(403, 'Patient profile not found');
                }
            } elseif (!$user->hasRole('doctor') && !$user->hasRole('admin')) {
                abort(403, 'Unauthorized');
            }

            return Storage::disk('s3')->download($path);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Download failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * List patient documents
     */
    public function list()
    {
        $user = Auth::user();

        if (!$user->hasRole('patient')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $patient = \App\Models\Patient::where('email', $user->email)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient profile not found'], 404);
        }

        try {
            // List all files in patient's folder
            $files = Storage::disk('s3')->files('medical_documents');
            
            return response()->json([
                'files' => $files,
                'count' => count($files),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to list documents: ' . $e->getMessage()], 500);
        }
    }
}
