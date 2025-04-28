@extends('layouts.app')

@section('title', 'Project Details')

@section('content')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .button-hover:hover {
            animation: bounce 0.5s ease-in-out;
        }
        .button-scale:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container mx-auto max-w-3xl px-4 py-8 animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-black">Project Details</h2>
            <a href="{{ route('projects.index') }}" class="text-sm bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition button-hover button-scale">Back</a>
        </div>

        <!-- Project Info Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-6 space-y-4 animate-fade-in">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="animate-slide-in" style="animation-delay: 0.1s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-project-diagram mr-2 text-indigo-500"></i>Project Name</dt>
                    <dd class="mt-1 text-gray-900">{{ $project->name }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.15s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-calendar-alt mr-2 text-green-500"></i>Start Date</dt>
                    <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.2s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-calendar-alt mr-2 text-red-500"></i>End Date</dt>
                    <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.25s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-user mr-2 text-blue-500"></i>Created By</dt>
                    <dd class="mt-1 text-gray-900">{{ $project->creator->username ?? 'N/A' }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.3s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-user-tie mr-2 text-teal-500"></i>Assigned Manager</dt>
                    <dd class="mt-1 text-gray-900">{{ $project->manager->username ?? 'N/A' }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.35s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-info-circle mr-2 text-yellow-500"></i>Status</dt>
                    <dd class="mt-1">
                        <span class="inline-block px-2 py-1 text-sm rounded
                            {{ $project->status == 'Completed' ? 'bg-green-100 text-green-800' :
                               ($project->status == 'In Progress' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800') }}">
                            {{ $project->status }}
                        </span>
                    </dd>
                </div>
            </div>

            <div class="mt-4 animate-slide-in" style="animation-delay: 0.4s;">
                <dt class="text-sm font-medium text-gray-500"><i class="fas fa-align-left mr-2 text-gray-500"></i>Description</dt>
                <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $project->description ?? 'No description provided.' }}</dd>
            </div>

            @if($project->status === 'Completed' && $project->notes)
                <div class="bg-yellow-100 p-4 rounded-md mt-4 animate-pulse">
                    <h4 class="text-yellow-800 font-semibold"><i class="fas fa-sticky-note mr-2"></i>Completion Notes</h4>
                    <p class="text-yellow-900 mt-1">{{ $project->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Manager Status Update -->
        @if(auth()->user()->role === 'Manager' && $project->manager_id === auth()->id() && $project->status !== 'Completed')
            <div class="bg-white shadow-md rounded-lg p-6 mb-6 animate-fade-in">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Update Status</h3>
                <form action="{{ route('projects.updateStatus', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#28A745] focus:border-[#28A745]">
                        <option value="Pending" {{ $project->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ $project->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ $project->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <textarea name="notes" placeholder="Add notes (required if completing)"
                              class="w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('notes', $project->notes) }}</textarea>
                    @error('notes') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition button-hover button-scale">
                        Update Status
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
