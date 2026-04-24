@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Book an Appointment</h1>
        <p class="text-gray-600 mt-2">Schedule your next visit with a doctor</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <h3 class="text-red-800 font-bold mb-2">Validation Errors:</h3>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('appointments.book') }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf

        <!-- Select Doctor -->
        <div class="mb-6">
            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                Doctor <span class="text-red-500">*</span>
            </label>
            <select name="doctor_id" id="doctor_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('doctor_id') border-red-500 @enderror" required>
                <option value="">-- Select a Doctor --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                        Dr. {{ $doctor->name }} ({{ $doctor->specialty->name }})
                    </option>
                @endforeach
            </select>
            @error('doctor_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Appointment Date -->
        <div class="mb-6">
            <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                Date <span class="text-red-500">*</span>
            </label>
            <input type="date" name="appointment_date" id="appointment_date" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_date') border-red-500 @enderror"
                   value="{{ old('appointment_date') }}" required min="{{ now()->format('Y-m-d') }}">
            @error('appointment_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Appointment Time -->
        <div class="mb-6">
            <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                Time <span class="text-red-500">*</span>
            </label>
            <input type="time" name="appointment_time" id="appointment_time"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_time') border-red-500 @enderror"
                   value="{{ old('appointment_time') }}" required>
            @error('appointment_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reason for Visit -->
        <div class="mb-6">
            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                Reason for Visit
            </label>
            <textarea name="reason" id="reason" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reason') border-red-500 @enderror"
                      placeholder="Describe your symptoms or reason for the visit...">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                ✓ Book Appointment
            </button>
            <a href="{{ route('dashboard') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition text-center">
                ✕ Cancel
            </a>
        </div>
    </form>
</div>
@endsection
