@extends('layouts.app')

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
            <h2 class="text-3xl font-bold text-black">Users List</h2>
            @if(auth()->user()->role === 'Admin')
                <a href="{{ route('users.create') }}" class="bg-[#28A745] text-white px-4 py-2 rounded hover:bg-[#218838] transition duration-150">Create User</a>
            @elseif(auth()->user()->role === 'Manager')
                <a href="{{ route('users.createEmployee') }}" class="bg-[#28A745] text-white px-4 py-2 rounded hover:bg-[#218838] transition duration-150">Add Employee</a>
            @endif
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-6">
            <div class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full border border-[#D3D3D3] rounded-l px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745]">
                <button type="submit" class="bg-[#28A745] text-white px-4 py-2 rounded-r hover:bg-[#218838] transition duration-150">Search</button>
            </div>
        </form>

        <!-- Users Grid -->
        @if(!$users || $users->isEmpty())
            <p class="text-center text-gray-600">No users found.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($users as $user)
                    <div class="bg-white shadow-md rounded-lg p-4 transition duration-300 cursor-pointer card-hover animate-fade-in"
                         onclick="window.location.href='{{ route('users.show', $user->id) }}'"
                         style="animation-delay: {{ $loop->index * 0.1 }}s;">                        <h3 class="text-lg font-semibold text-black mb-2">{{ $user->username }}</h3>

                        <div class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-envelope mr-1 text-blue-500"></i> Email: {{ $user->email }}
                        </div>

                        <div class="text-sm mb-1">
                            <i class="fas fa-check-circle mr-1 {{ $user->email_verified_at ? 'text-green-500' : 'text-red-500' }}"></i>
                            Email Status: {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                        </div>

                        <div class="text-sm mb-1">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                {{ $user->role == 'Admin' ? 'bg-blue-100 text-blue-800' : ($user->role == 'Manager' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $user->role }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-users mr-1 text-purple-500"></i> Group:
                            @if($user->groups && $user->groups->isNotEmpty())
                                {{ $user->groups->pluck('name')->join(', ') }}
                            @else
                                None
                            @endif
                        </div>

                        @if(auth()->user()->role === 'Admin')
                            <div class="flex justify-end space-x-2" onclick="event.stopPropagation()">
                                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-150">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
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
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
