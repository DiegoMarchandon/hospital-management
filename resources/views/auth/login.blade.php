@extends('layouts.app')

@section('title', 'Login - Hospital Management System')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">🏥 Hospital Management</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror"
                        required
                    >
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2"><strong>Demo Credentials:</strong></p>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>Admin:</strong> admin@hospital.com</p>
                    <p><strong>Doctor:</strong> doctor1@hospital.com</p>
                    <p><strong>Patient:</strong> patient1@hospital.com</p>
                    <p><strong>Password (all):</strong> password</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
