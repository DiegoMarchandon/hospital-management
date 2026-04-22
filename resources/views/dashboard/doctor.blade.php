@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800">Welcome, Dr. {{ auth()->user()->name }}</h1>
    @if($doctor)
        <p class="text-gray-600">Specialty: <strong>{{ $doctor->specialty->name }}</strong></p>
    @else
        <p class="text-yellow-600">⚠️ Your doctor profile is being set up. Please contact support.</p>
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
                <p class="text-gray-600 text-sm">Working Days</p>
                <p class="text-3xl font-bold text-purple-600">{{ $schedule->count() }}</p>
            </div>
            <div class="text-4xl text-purple-200">⏰</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Upcoming Appointments -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Upcoming Appointments</h2>
        <div class="space-y-3">
            @forelse($upcoming_appointments as $appointment)
                <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-bold text-lg">{{ $appointment->patient->name }}</p>
                            <p class="text-sm text-gray-600">📅 {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}</p>
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
                        <p class="text-sm text-gray-700"><strong>Reason:</strong> {{ $appointment->reason }}</p>
                    @endif
                    <div class="mt-3 pt-3 border-t flex space-x-2">
                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View Patient</button>
                        <button class="text-sm text-green-600 hover:text-green-800 font-medium">Confirm</button>
                        <button class="text-sm text-red-600 hover:text-red-800 font-medium">Cancel</button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p class="text-lg">No upcoming appointments</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Doctor Info & Schedule -->
    <div>
        <!-- Doctor Info Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Your Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-medium">{{ $doctor->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium">{{ $doctor->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Phone</p>
                    <p class="font-medium">{{ $doctor->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">License</p>
                    <p class="font-medium">{{ $doctor->license_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Specialty</p>
                    <p class="font-medium">{{ $doctor->specialty->name }}</p>
                </div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Working Schedule</h2>
            @if($schedule->count() > 0)
                <div class="space-y-2">
                    @foreach($schedule as $slot)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <span class="text-sm font-medium">
                                {{ $slot->day_of_week }}
                            </span>
                            <span class="text-sm text-gray-600">{{ $slot->start_time }} - {{ $slot->end_time }}</span>
                            <span class="px-2 py-1 text-xs rounded
                                @if($slot->is_available) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">
                                {{ $slot->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No schedule configured</p>
            @endif
        </div>
    </div>
</div>
@endsection
