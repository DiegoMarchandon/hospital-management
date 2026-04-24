@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-gray-200">404</h1>
        </div>
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Oops! Page Not Found</h2>
        <p class="text-xl text-gray-600 mb-8">
            Sorry, the page you're looking for doesn't exist or has been moved.
        </p>
        <div class="space-y-3">
            <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                🏠 Go Home
            </a>
            <p class="text-gray-500 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700">Go to Dashboard</a>
                @endauth
            </p>
        </div>
    </div>
</div>
@endsection
