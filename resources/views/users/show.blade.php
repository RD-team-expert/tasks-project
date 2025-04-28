@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <style>
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

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

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

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
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
            <h2 class="text-3xl font-bold text-black">User Details</h2>
            <div class="space-x-2">
                <a href="{{ route('users.index') }}" class="bg-[#E0E0E0] text-gray-600 px-4 py-2 rounded transition duration-300 button-hover button-scale">Back to Users</a>
                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded transition duration-300 button-hover button-scale">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded transition duration-300 button-hover button-scale" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- User Details Card -->
        <div class="bg-white shadow-md rounded-lg p-6 animate-fade-in">
            <h3 class="text-xl font-semibold text-black mb-4 animate-pulse">{{ $user->username }}</h3>
            <div class="space-y-3">
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.1s;">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    <strong>Email:</strong> {{ $user->email }}
                </p>
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.2s;">
                    <i class="fas fa-check-circle mr-2 {{ $user->email_verified_at ? 'text-green-500' : 'text-red-500' }}"></i>
                    <strong>Email Verified:</strong> {{ $user->email_verified_at ?? 'Not Verified' }}
                </p>
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.3s;">
                    <i class="fas fa-user-shield mr-2 text-purple-500"></i>
                    <strong>Role:</strong> {{ $user->role }}
                </p>
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.4s;">
                    <i class="fas fa-id-badge mr-2 text-indigo-500"></i>
                    <strong>Position ID:</strong> {{ $user->position_id ?? 'N/A' }}
                </p>
                <p class="text-gray-600 animate-slide-in" style="animation-delay: 0.5s;">
                    <i class="fas fa-fingerprint mr-2 text-gray-500"></i>
                    <strong>Remember Token:</strong> {{ $user->remember_token ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>
@endsection
