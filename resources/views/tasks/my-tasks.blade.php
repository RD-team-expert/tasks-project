@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">My Tasks</h2>

        @forelse($tasks as $task)
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ $task->name }}</h3>
                <p class="text-gray-600"><strong>Project:</strong> {{ $task->project->name }}</p>
                <p class="text-gray-600"><strong>Status:</strong> {{ $task->status }}</p>
                <a href="{{ route('tasks.editDetails', $task) }}" class="inline-block mt-3 bg-blue-500 text-black py-1 px-3 rounded hover:bg-blue-600">
                    Edit Task Details
                </a>
            </div>
        @empty
            <p class="text-gray-500">You currently have no tasks assigned.</p>
        @endforelse
    </div>
@endsection
