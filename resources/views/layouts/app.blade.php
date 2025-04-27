<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Dashboard'))</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')

</head>
<body class="bg-gray-100 flex min-h-screen" x-data="{ sidebarCollapsed: false }">
<!-- Sidebar with Integrated Navbar -->
<div class="sidebar" :class="{ collapsed: sidebarCollapsed }">
    <!-- Sidebar Header (Navbar Content) -->
    <div class="sidebar-header">
        <div class="flex items-center justify-between">
            <h2>{{ config('app.name', 'Dashboard') }}</h2>
            <button class="toggle-sidebar" @click="sidebarCollapsed = !sidebarCollapsed">
                <i class="fas fa-bars text-white"></i>
            </button>
        </div>
        @auth
            <div class="flex flex-col space-y-3">
                <!-- Notifications Dropdown -->
                <div class="relative notifications" x-show="!sidebarCollapsed">
                    <button onclick="toggleNotifications()" class="w-full px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition text-sm">
                        Notifications ({{ auth()->user()->unreadNotifications->count() }})
                    </button>
                    <div id="notificationDropdown" class="hidden absolute left-0 top-full mt-2 bg-white shadow-lg rounded p-4 w-80 notification-dropdown">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <div class="border-b py-2">
                                <p class="text-sm">
                                    <strong>{{ $notification->data['completed_by'] }}</strong> completed task
                                    <strong>{{ $notification->data['task_name'] }}</strong>.
                                </p>
                                <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                <a href="{{ route('tasks.show', $notification->data['task_id']) }}" class="text-blue-500 text-sm block mt-1 hover:underline">
                                    View Task
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500">No new notifications.</p>
                        @endforelse
                    </div>
                </div>
                <!-- User Info -->
                <span class="text-sm text-gray-300 user-info" x-show="!sidebarCollapsed">{{ auth()->user()->username }}</span>
            </div>
        @endauth
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar-nav">
        @auth
            @if(auth()->user()->role === 'Employee')
                <a href="{{ route('projects.myProjects') }}" class="{{ request()->routeIs('projects.myProjects') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span x-show="!sidebarCollapsed">My Projects</span>
                </a>
                <a href="{{ route('tasks.myTasks') }}" class="{{ request()->routeIs('tasks.myTasks') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span x-show="!sidebarCollapsed">My Tasks</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span x-show="!sidebarCollapsed">Dashboard</span>
                </a>
                <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span x-show="!sidebarCollapsed">Projects</span>
                </a>
                <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span x-show="!sidebarCollapsed">Tasks</span>
                </a>
                <a href="{{ route('groups.index') }}" class="{{ request()->routeIs('groups.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span x-show="!sidebarCollapsed">Groups</span>
                </a>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i>
                    <span x-show="!sidebarCollapsed">Users</span>
                </a>

                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('questions.index') }}" class="{{ request()->routeIs('questions.*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span x-show="!sidebarCollapsed">Questions</span>
                    </a>
                @endif

                <div style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <a href="{{ route('projects.create') }}">
                        <i class="fas fa-plus-circle"></i>
                        <span x-show="!sidebarCollapsed">New Project</span>
                    </a>
                    <a href="{{ route('tasks.create') }}">
                        <i class="fas fa-plus-square"></i>
                        <span x-show="!sidebarCollapsed">New Task</span>
                    </a>

                    @if(auth()->user()->role === 'Admin')
                        <a href="{{ route('groups.create') }}">
                            <i class="fas fa-user-plus"></i>
                            <span x-show="!sidebarCollapsed">New Group</span>
                        </a>
                        <a href="{{ route('questions.create') }}">
                            <i class="fas fa-question"></i>
                            <span x-show="!sidebarCollapsed">New Question</span>
                        </a>
                        <a href="{{ route('users.create') }}">
                            <i class="fas fa-user-plus"></i>
                            <span x-show="!sidebarCollapsed">New User</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'Manager')
                        <a href="{{ route('users.createEmployee') }}">
                            <i class="fas fa-user-tie"></i>
                            <span x-show="!sidebarCollapsed">Add Employee</span>
                        </a>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn inline-flex items-center justify-center w-full px-4 py-2 mt-6 text-white bg-red-600 rounded hover:bg-red-700 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span x-show="!sidebarCollapsed">Logout</span>
                </button>
            </form>
        @endauth
    </div>
</div>

<!-- Main Content Area -->
<div class="content">
    <main class="container mx-auto px-4">
        @yield('content')
    </main>

</div>

@stack('scripts')
<script>
    function toggleNotifications() {
        document.getElementById('notificationDropdown').classList.toggle('hidden');
    }
</script>
</body>
</html>
