@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Welcome, {{ auth()->user()->username }}!</h1>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-black py-2 px-4 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>

        <!-- Role-based Navigation -->
        @if(auth()->user()->role === 'Employee')
            <div class="space-y-4">
                <a href="{{ route('projects.myProjects') }}" class="block bg-blue-500 text-black py-2 px-4 rounded hover:bg-blue-600">
                    My Projects
                </a>
                <a href="{{ route('tasks.myTasks') }}" class="block bg-green-500 text-black py-2 px-4 rounded hover:bg-green-600">
                    My Tasks
                </a>
            </div>
        @elseif(auth()->user()->role === 'Manager' || auth()->user()->role === 'Admin')
            <div class="space-y-4">
                <a href="{{ route('projects.index') }}" class="block bg-blue-500 text-black py-2 px-4 rounded hover:bg-blue-600">
                    All Projects
                </a>
                <a href="{{ route('tasks.index') }}" class="block bg-green-500 text-black py-2 px-4 rounded hover:bg-green-600">
                    All Tasks
                </a>
                <a href="{{ route('groups.index') }}" class="block bg-yellow-500 text-black py-2 px-4 rounded hover:bg-yellow-600">
                    All Groups
                </a>
                <a href="{{ route('questions.index') }}" class="block bg-pink-500 text-black py-2 px-4 rounded hover:bg-pink-600">
                    All Questions
                </a>
                <a href="{{ route('users.index') }}" class="block bg-teal-500 text-black py-2 px-4 rounded hover:bg-teal-600">
                    All Users
                </a>
                <a href="{{ route('projects.create') }}" class="block bg-indigo-500 text-black py-2 px-4 rounded hover:bg-indigo-600">
                    Create New Project
                </a>
                <a href="{{ route('tasks.create') }}" class="block bg-purple-500 text-black py-2 px-4 rounded hover:bg-purple-600">
                    Create New Task
                </a>
                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('groups.create') }}" class="block bg-yellow-600 text-black py-2 px-4 rounded hover:bg-yellow-700">
                        Create New Group
                    </a>
                    <a href="{{ route('questions.create') }}" class="block bg-pink-600 text-black py-2 px-4 rounded hover:bg-pink-700">
                        Create New Question
                    </a>
                    <a href="{{ route('users.create') }}" class="block bg-teal-600 text-black py-2 px-4 rounded hover:bg-teal-700">
                        Create New User
                    </a>
                @endif
                @if(auth()->user()->role === 'Manager')
                    <a href="{{ route('users.createEmployee') }}" class="block bg-teal-600 text-black py-2 px-4 rounded hover:bg-teal-700">
                        Add Employees
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection
