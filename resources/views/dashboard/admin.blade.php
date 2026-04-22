@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800">Admin Dashboard</h1>
    <p class="text-gray-600">Welcome, {{ auth()->user()->name }}! Here's your hospital overview.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Doctors</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['doctors'] }}</p>
            </div>
            <div class="text-4xl text-blue-200">👨‍⚕️</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Patients</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['patients'] }}</p>
            </div>
            <div class="text-4xl text-green-200">👤</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Appointments</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['appointments'] }}</p>
            </div>
            <div class="text-4xl text-purple-200">📅</div>
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
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Appointments -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Recent Appointments</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Doctor</th>
                        <th class="px-4 py-2 text-left">Patient</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_appointments as $appointment)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">{{ $appointment->doctor->name }}</td>
                            <td class="px-4 py-3">{{ $appointment->patient->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="4" class="px-4 py-3 text-gray-500 text-center">No appointments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">System Status</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Active Doctors</span>
                <span class="font-bold text-lg">{{ $doctors->count() }}</span>
            </div>
            <div class="border-t"></div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Registered Patients</span>
                <span class="font-bold text-lg">{{ $patients->count() }}</span>
            </div>
            <div class="border-t"></div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Success Rate</span>
                <span class="font-bold text-lg text-green-600">
                    @if($stats['appointments'] > 0)
                        {{ round(($stats['completed_appointments'] / $stats['appointments']) * 100, 1) }}%
                    @else
                        N/A
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Doctors List -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4">Doctors Directory</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($doctors as $doctor)
            <div class="border rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="text-3xl mr-3">👨‍⚕️</div>
                    <div>
                        <h3 class="font-bold">{{ $doctor->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $doctor->specialty->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-2"><strong>Email:</strong> {{ $doctor->email }}</p>
                <p class="text-sm text-gray-600"><strong>License:</strong> {{ $doctor->license_number }}</p>
            </div>
        @empty
            <p class="text-gray-500 col-span-full">No doctors found</p>
        @endforelse
    </div>
</div>
@endsection
