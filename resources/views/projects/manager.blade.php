@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Assigned Projects</h2>
        @forelse($projects as $project)
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">
                <p><strong>Name:</strong> {{ $project->name }}</p>
                <p><strong>Status:</strong> {{ $project->status }}</p>
                <form action="{{ route('projects.updateStatus', $project) }}" method="POST" class="mt-2">
                    @csrf
                    <select name="status" class="border-gray-300 rounded-md">
                        <option value="Pending" {{ $project->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ $project->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ $project->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @if($project->status !== 'Completed')
                        <textarea name="notes" placeholder="Add notes if completing" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $project->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    @endif
                    <button type="submit" class="bg-blue-500 text-black px-2 py-1 rounded ml-2">Update</button>
                </form>
            </div>
        @empty
            <p>No assigned projects found.</p>
        @endforelse
        {{ $projects->links() }}
        <a href="{{ route('projects.completed') }}" class="text-blue-500 hover:underline">View Completed Projects</a>
    </div>
@endsection
