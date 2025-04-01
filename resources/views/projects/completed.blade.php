@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Completed Projects</h2>
        @forelse($completedProjects as $project)
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">
                <p><strong>Name:</strong> {{ $project->name }}</p>
                <p><strong>Status:</strong> {{ $project->status }}</p>
                <p><strong>Notes:</strong> {{ $project->notes ?? 'No notes provided.' }}</p>
            </div>
        @empty
            <p>No completed projects found.</p>
        @endforelse
        {{ $completedProjects->links() }}
    </div>
@endsection
