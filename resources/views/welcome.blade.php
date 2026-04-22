<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">🏥 HMS</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">
                Hospital Management System
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Streamline your healthcare operations with our modern management platform
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-4xl mb-3">👨‍⚕️</div>
                    <h3 class="text-lg font-bold mb-2">For Doctors</h3>
                    <p class="text-gray-600">Manage your schedule and patient appointments</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-4xl mb-3">👤</div>
                    <h3 class="text-lg font-bold mb-2">For Patients</h3>
                    <p class="text-gray-600">Book appointments and view your medical records</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-4xl mb-3">⚙️</div>
                    <h3 class="text-lg font-bold mb-2">For Admins</h3>
                    <p class="text-gray-600">Monitor hospital operations and manage staff</p>
                </div>
            </div>

            @guest
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition">
                    Get Started
                </a>
                <p class="text-gray-600 mt-4 text-sm">Demo credentials available on login page</p>
            @else
                <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition">
                    Go to Dashboard
                </a>
            @endguest
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-600">
            <p>&copy; 2026 Hospital Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
