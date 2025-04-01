<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Project Management')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Navbar -->
<nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">Project Management</a>
        <div class="flex items-center space-x-4">

            <!-- Notifications Dropdown -->
            <div class="relative">
                <button onclick="toggleNotifications()" class="px-4 py-2 bg-gray-300 rounded">
                    Notifications ({{ auth()->user()->unreadNotifications->count() }})
                </button>

                <div id="notificationDropdown" class="hidden absolute right-0 bg-white shadow-lg rounded p-4 w-80 max-h-64 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="border-b py-2">
                            <p class="text-sm">
                                <strong>{{ $notification->data['completed_by'] }}</strong> completed task
                                <strong>{{ $notification->data['task_name'] }}</strong>.
                            </p>
                            <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                            <a href="{{ route('tasks.show', $notification->data['task_id']) }}" class="text-blue-500 text-sm block mt-1">
                                View Task
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500">No new notifications.</p>
                    @endforelse
                </div>
            </div>

            <!-- User Info & Logout -->
            <span class="text-gray-700">{{ auth()->user()->username }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-150">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8 flex-grow">
    @yield('content')
</main>

<!-- Footer -->
<footer class="py-4 bg-white shadow-inner">
    <div class="container mx-auto px-4 text-center text-gray-600">
        &copy; {{ date('Y') }} Project Management System
    </div>
</footer>

<!-- Scripts -->
@stack('scripts')
<script>
    function toggleNotifications() {
        document.getElementById('notificationDropdown').classList.toggle('hidden');
    }
</script>
</body>
</html>
