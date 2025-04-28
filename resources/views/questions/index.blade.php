@extends('layouts.app')

@section('content')
    <style>
        /* Custom Fade-In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Custom Scale-Up Animation on Hover */
        .card-hover:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-black">Questions List</h2>
            @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager')
                <a href="{{ route('questions.create') }}" class="bg-[#28A745] text-white px-4 py-2 rounded hover:bg-[#218838] transition duration-150">Create Question</a>
            @endif
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('questions.index') }}" class="mb-6">
            <div class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search questions..." class="w-full border border-[#D3D3D3] rounded-l px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745]">
                <button type="submit" class="bg-[#28A745] text-white px-4 py-2 rounded-r hover:bg-[#218838] transition duration-150">Search</button>
            </div>
        </form>

        <!-- Questions Grid -->
        @if(!$questions || $questions->isEmpty())
            <p class="text-center text-gray-600">No questions found.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($questions as $question)
                    <div class="bg-white shadow-md rounded-lg p-4 transition duration-300 cursor-pointer card-hover animate-fade-in" onclick="window.location.href='{{ route('questions.show', $question->id) }}'" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <h3 class="text-lg font-semibold text-black mb-2 line-clamp-2">{{ $question->text }}</h3>
                        <div class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-user mr-1 text-blue-500"></i> Created by: {{ $question->creator->username ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-tasks mr-1 text-purple-500"></i> Used in: {{ $question->tasks_count }} {{ Str::plural('task', $question->tasks_count) }}
                        </div>
                        @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager')
                            <div class="flex justify-end space-x-2" onclick="event.stopPropagation()">
                                <a href="{{ route('questions.edit', $question->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-150">Edit</a>
                                <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-150" onclick="event.stopPropagation(); return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $questions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
