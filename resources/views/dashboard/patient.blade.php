@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}</h1>
    @if($patient)
        <p class="text-gray-600">Patient ID: <strong>{{ $patient->id_number }}</strong></p>
    @else
        <p class="text-yellow-600">⚠️ Your patient profile is being set up. Please contact support.</p>
    @endif
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Appointments</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_appointments'] }}</p>
            </div>
            <div class="text-4xl text-blue-200">📅</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Pending</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['pending_appointments'] }}</p>
            </div>
            <div class="text-4xl text-orange-200">⏳</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Completed</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['completed_appointments'] }}</p>
            </div>
            <div class="text-4xl text-green-200">✅</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Medical Records</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['medical_records'] }}</p>
            </div>
            <div class="text-4xl text-purple-200">📋</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Appointments -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Your Appointments</h2>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($appointments as $appointment)
                <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-lg">Dr. {{ $appointment->doctor->name }}</p>
                            <p class="text-sm text-gray-600">
                                🏥 {{ $appointment->doctor->specialty->name }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                📅 {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    @if($appointment->reason)
                        <p class="text-sm text-gray-700 mt-2"><strong>Reason:</strong> {{ $appointment->reason }}</p>
                    @endif
                    <div class="mt-3 pt-3 border-t flex space-x-2">
                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View Details</button>
                        @if($appointment->status !== 'cancelled')
                            <button class="text-sm text-red-600 hover:text-red-800 font-medium">Cancel</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p class="text-lg">No appointments scheduled</p>
                    <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Book an Appointment
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Patient Info & Quick Actions -->
    <div>
        <!-- Patient Info Card -->
        @if($patient)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Your Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="font-medium">{{ $patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $patient->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium">{{ $patient->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date of Birth</p>
                        <p class="font-medium">{{ $patient->date_of_birth->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Patient ID</p>
                        <p class="font-medium">{{ $patient->id_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="font-medium">{{ $patient->address }}, {{ $patient->city }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 rounded-lg shadow p-6 mb-6 border border-yellow-200">
                <h2 class="text-xl font-bold mb-4 text-yellow-800">⚠️ Patient Profile Not Found</h2>
                <p class="text-yellow-700">Your patient profile is being set up. Please contact the hospital administrator or try logging in again.</p>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-blue-50 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <button class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                    📅 Book Appointment
                </button>
                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-medium">
                    📋 View Medical Records
                </button>
                <a href="{{ route('documents.upload.form') }}" class="block w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium text-center">
                    📤 Upload Document to S3
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Medical Records -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Medical History</h2>
    @if($medical_records->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Doctor</th>
                        <th class="px-4 py-2 text-left">Diagnosis</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medical_records as $record)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $record->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">Dr. {{ $record->doctor->name }}</td>
                            <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($record->diagnosis, 50) ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <button class="text-blue-600 hover:text-blue-800 font-medium">View</button>
                                <button class="text-green-600 hover:text-green-800 font-medium ml-2">📥 Download</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <p>No medical records found</p>
        </div>
    @endif
</div>
@endsection
