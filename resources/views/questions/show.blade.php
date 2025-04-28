@extends('layouts.app')

@section('title', 'Question Details')

@section('content')
    <style>
        /* Fade-In Animation for Card */
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

        /* Pulse Animation for Question Text */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Slide-In Animation for Detail Items */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        /* Bounce Animation on Hover for Buttons */
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }
        .button-hover:hover {
            animation: bounce 0.5s ease-in-out;
        }

        /* Scale-Up Animation on Hover for Buttons */
        .button-scale:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-black">Question Details</h2>
            <div class="space-x-2">
                <a href="{{ route('questions.index') }}" class="bg-[#E0E0E0] text-gray-600 px-4 py-2 rounded transition duration-300 button-hover button-scale">Back to Questions</a>
                @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager')
                    <a href="{{ route('questions.edit', $question->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded transition duration-300 button-hover button-scale">Edit</a>
                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded transition duration-300 button-hover button-scale" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Question Details Card -->
        <div class="bg-white shadow-md rounded-lg p-6 animate-fade-in">
            <h3 class="text-xl font-semibold text-black mb-4 animate-pulse">{{ $question->text }}</h3>
            <div class="space-y-3">
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.1s;">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    <strong>Created by:</strong> {{ $question->creator->username ?? 'N/A' }}
                </p>
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.2s;">
                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                    <strong>Created on:</strong> {{ $question->created_at->format('M d, Y') }}
                </p>
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.3s;">
                    <i class="fas fa-tasks mr-2 text-purple-500"></i>
                    <strong>Associated Tasks:</strong>
                @if($question->tasks->isNotEmpty())
                    <ul class="list-disc list-inside mt-1">
                        @foreach($question->tasks as $task)
                            <li>
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-[#28A745] hover:underline">
                                    {{ $task->name }}
                                </a>
                                (Project: {{ $task->project->name ?? 'N/A' }})
                            </li>
                        @endforeach
                    </ul>
                @else
                    None
                    @endif
                    </p>
            </div>
        </div>
    </div>
@endsection
