<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarCollapsed: false, notificationsOpen: false }" x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); $watch('darkMode', value => { localStorage.setItem('darkMode', value); document.documentElement.classList.toggle('dark', value); })">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Dashboard'))</title>
    <!-- Replace Vite with direct asset links -->
    <link rel="stylesheet" href="{{ url('build/assets/app-3zy86uaP.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ url('build/assets/app-Bo-u61x1.js') }}"></script>
    @stack('styles')
    <style>
        /* Global Styles */
        :root {
            --bg-body: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            --bg-sidebar: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
            --bg-sidebar-active: #2563eb;
            --bg-notification-btn: #3b82f6;
            --bg-notification-btn-hover: #2563eb;
            --bg-logout: #dc2626;
            --bg-logout-hover: #b91c1c;
            --text-primary: #1f2937;
            --text-secondary: #e5e7eb;
            --text-sidebar: #ffffff;
            --scrollbar-thumb: #2563eb;
            --scrollbar-thumb-hover: #1e40af;
            --scrollbar-track: rgba(255, 255, 255, 0.1);
        }

        .dark {
            --bg-body: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            --bg-sidebar: linear-gradient(180deg, #1f2937 0%, #374151 100%);
            --bg-sidebar-active: #4b5563;
            --bg-notification-btn: #4b5563;
            --bg-notification-btn-hover: #6b7280;
            --bg-logout: #991b1b;
            --bg-logout-hover: #7f1d1d;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --text-sidebar: #d1d5db;
            --scrollbar-thumb: #4b5563;
            --scrollbar-thumb-hover: #6b7280;
            --scrollbar-track: rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(90deg);
            }
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            color: var(--text-sidebar);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease, transform 0.3s ease;
            animation: slideInLeft 0.5s ease-out;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-sidebar);
            margin: 0;
        }

        .toggle-sidebar,
        .dark-mode-toggle {
            background: none;
            border: none;
            color: var(--text-sidebar);
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .toggle-sidebar.active {
            animation: rotate 0.3s ease forwards;
        }

        .sidebar-nav {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
            border-radius: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--scrollbar-thumb);
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-thumb-hover);
        }

        /* Firefox Scrollbar */
        .sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
        }

        .sidebar-nav a,
        .sidebar-nav button {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-sidebar);
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s ease, transform 0.2s ease, color 0.3s ease;
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover,
        .sidebar-nav button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            animation: bounce 0.4s ease;
        }

        .sidebar-nav a.active {
            background: var(--bg-sidebar-active);
            color: var(--text-sidebar);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .sidebar-nav span {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-nav span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .logout-btn {
            margin-top: auto;
            background: var(--bg-logout);
            color: var(--text-sidebar);
            flex-shrink: 0;
        }

        .logout-btn:hover {
            background: var(--bg-logout-hover);
        }

        /* Notification Dropdown */
        .notifications .notification-dropdown {
            animation: scaleIn 0.3s ease-out;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 10;
        }

        .dark .notifications .notification-dropdown {
            background: #1f2937;
            color: var(--text-secondary);
        }

        .notifications button {
            background: var(--bg-notification-btn);
            color: var(--text-sidebar);
            border-radius: 8px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .notifications button:hover {
            background: var(--bg-notification-btn-hover);
            transform: scale(1.05);
        }

        /* Main Content */
        .content {
            margin-left: 260px;
            padding: 2rem;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
            animation: fadeInUp 0.5s ease-out;
        }

        .sidebar.collapsed~.content {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }

            .sidebar.collapsed~.content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar-nav span {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }
        }
    </style>
</head>

<body class="flex min-h-screen">
    <!-- Sidebar with Integrated Navbar -->
    <div class="sidebar" :class="{ collapsed: sidebarCollapsed }">
        <!-- Sidebar Header (Navbar Content) -->
        <div class="sidebar-header">
            <div class="flex items-center justify-between">
                <h2 x-show="!sidebarCollapsed">{{ config('app.name', 'Dashboard') }}</h2>
                <div class="flex items-center space-x-2">
                    <button class="dark-mode-toggle" @click="darkMode = !darkMode">
                        <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg"></i>
                    </button>
                    <button class="toggle-sidebar" @click="sidebarCollapsed = !sidebarCollapsed" :class="{ 'active': sidebarCollapsed }">
                        <i class="fas fa-bars text-white"></i>
                    </button>
                </div>
            </div>
            @auth
            <div class="flex flex-col space-y-3 mt-4">
                <!-- Notifications Dropdown -->
                <div class="relative notifications" x-show="!sidebarCollapsed">
                    <button @click="notificationsOpen = !notificationsOpen" class="w-full px-4 py-2 text-sm">
                        Notifications ({{ auth()->user()->unreadNotifications->count() }})
                    </button>
                    <div x-show="notificationsOpen" class="notification-dropdown absolute left-0 top-full mt-2 p-4 w-80">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="border-b py-2">
                            <p class="text-sm">
                                <strong>{{ $notification->data['completed_by'] }}</strong> completed task
                                <strong>{{ $notification->data['task_name'] }}</strong>.
                            </p>
                            <small class="text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                            <a href="{{ route('tasks.show', $notification->data['task_id']) }}" class="text-blue-500 dark:text-blue-400 text-sm block mt-1 hover:underline">
                                View Task
                            </a>
                        </div>
                        @empty
                        <p class="text-gray-500 dark:text-gray-400">No new notifications.</p>
                        @endforelse
                    </div>
                </div>
                <!-- User Info -->
                <span class="text-sm user-info" x-show="!sidebarCollapsed">{{ auth()->user()->username }}</span>
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
                <button type="submit" class="logout-btn">
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
</body>