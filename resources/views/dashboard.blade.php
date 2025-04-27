@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-container max-w-5xl mx-auto p-8 rounded-lg">
        <h1 class="text-4xl font-bold mb-10 text-gray-800">Welcome, {{ auth()->user()->username }}!</h1>

        <div class="cards grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('users.index') }}" class="card bg-[#222] rounded-lg p-6 flex items-center space-x-4 shadow-[0_2px_5px_rgba(0,0,0,0.4)] border-l-4 border-[#22c55e] hover:shadow-lg transition-shadow" tabindex="0">
                <div class="icon text-[#22c55e] text-3xl">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="flex-1">
                    <div class="title text-sm text-[#aaa]">Total Users</div>
                    <div class="value text-2xl font-bold my-3 text-white">{{ $totalUsers }}</div>
                    <div class="footer-text text-xs text-[#aaa]">↑ The users in this period</div>
                </div>
            </a>
            <a href="{{ route('projects.index') }}" class="card bg-[#222] rounded-lg p-6 flex items-center space-x-4 shadow-[0_2px_5px_rgba(0,0,0,0.4)] border-l-4 border-[#f97316] hover:shadow-lg transition-shadow" tabindex="0">
                <div class="icon text-[#f97316] text-3xl">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="flex-1">
                    <div class="title text-sm text-[#aaa]">Total Projects</div>
                    <div class="value text-2xl font-bold my-3 text-white">{{ $totalProjects }}</div>
                    <div class="footer-text text-xs text-[#aaa]">↗ The projects in this period</div>
                </div>
            </a>
            <a href="{{ route('tasks.index') }}" class="card bg-[#222] rounded-lg p-6 flex items-center space-x-4 shadow-[0_2px_5px_rgba(0,0,0,0.4)] border-l-4 border-[#ef4444] hover:shadow-lg transition-shadow" tabindex="0">
                <div class="icon text-[#ef4444] text-3xl">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="flex-1">
                    <div class="title text-sm text-[#aaa]">Total Tasks</div>
                    <div class="value text-2xl font-bold my-3 text-white">{{ $totalTasks }}</div>
                    <div class="footer-text text-xs text-[#aaa]">↓ The tasks in this period</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Floating Action Button (FAB) -->
    <div x-data="{ open: false }" class="fixed bottom-6 right-6">
        <button @click="open = !open" class="bg-[#1abc9c] text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-[#16a085] transition">
            <i class="fas fa-plus text-xl"></i>
        </button>
        <div x-show="open" class="absolute bottom-16 right-0 bg-white rounded-lg shadow-lg p-4 w-48">
            <a href="{{ route('projects.create') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">New Project</a>
            <a href="{{ route('tasks.create') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">New Task</a>
            @if(auth()->user()->role === 'Admin')
                <a href="{{ route('groups.create') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">New Group</a>
                <a href="{{ route('users.create') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">New User</a>
            @endif
        </div>
    </div>
@endsection
