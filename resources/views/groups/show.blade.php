@extends('layouts.app')

@section('title', 'Group Details')

@section('content')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        .button-hover:hover {
            animation: bounce 0.5s ease-in-out;
        }

        .button-scale:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-black">Group Details</h2>
            <div class="space-x-2">
                <a href="{{ route('groups.index') }}" class="bg-[#E0E0E0] text-gray-600 px-4 py-2 rounded transition duration-300 button-hover button-scale">Back to Groups</a>
                <a href="{{ route('groups.edit', $group->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded transition duration-300 button-hover button-scale">Edit</a>
                <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded transition duration-300 button-hover button-scale" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>

        <!-- Group Details Card -->
        <div class="bg-white shadow-md rounded-lg p-6 animate-fade-in">
            <h3 class="text-xl font-semibold text-black mb-4 animate-pulse">{{ $group->name }}</h3>
            <div class="space-y-3">
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.1s;">
                    <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                    <strong>Manager:</strong> {{ $group->manager->username ?? 'N/A' }}
                </p>

                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.2s;">
                    <i class="fas fa-users mr-2 text-purple-500"></i>
                    <strong>Employees:</strong>
                @if($group->employees->isNotEmpty())
                    <ul class="list-disc list-inside mt-1">
                        @foreach($group->employees as $employee)
                            <li>{{ $employee->username }}</li>
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
