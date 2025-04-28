@extends('layouts.app')

@section('title', 'Tasks List')

@section('content')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .card-hover:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-black">Tasks List</h2>
            <a href="{{ route('tasks.create') }}" class="bg-[#28A745] text-white px-4 py-2 rounded hover:bg-[#218838] transition duration-150">Create Task</a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-6">
            <div class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..." class="w-full border border-[#D3D3D3] rounded-l px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745]">
                <button type="submit" class="bg-[#28A745] text-white px-4 py-2 rounded-r hover:bg-[#218838] transition duration-150">Search</button>
            </div>
        </form>

        <!-- Tasks Grid -->
        @if(!$tasks || $tasks->isEmpty())
            <p class="text-center text-gray-600">No tasks available.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($tasks as $task)
                    <div class="bg-white shadow-md rounded-lg p-4 transition duration-300 cursor-pointer card-hover animate-fade-in"
                         onclick="window.location.href='{{ route('tasks.show', $task->id) }}'"
                         style="animation-delay: {{ $loop->index * 0.1 }}s;">

                        <h3 class="text-lg font-semibold text-black mb-2 line-clamp-2">{{ $task->name }}</h3>
                        <p class="text-gray-600 line-clamp-2 mb-2">{{ $task->description }}</p>

                        <div class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-folder mr-1 text-indigo-500"></i> Project: {{ $task->project->name ?? 'N/A' }}
                        </div>

                        <div class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-green-500"></i> Start: {{ $task->start_date }}
                        </div>

                        <div class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-red-500"></i> End: {{ $task->end_date }}
                        </div>

                        <div class="text-sm mb-1">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                {{ $task->status == 'Completed' ? 'bg-green-100 text-green-800' : ($task->status == 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $task->status }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-user mr-1 text-blue-500"></i> Created by: {{ $task->creator->username ?? 'N/A' }}
                        </div>

                        <div class="flex justify-end space-x-2" onclick="event.stopPropagation()">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-150">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-150" onclick="event.stopPropagation(); return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $tasks->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
