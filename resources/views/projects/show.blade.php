@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">{{ $project->name }}</h2>

        <div class="bg-white shadow-md rounded-lg p-4 mb-4">
            <p><strong>Description:</strong> {{ $project->description ?? 'No description provided.' }}</p>
            <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
            <p><strong>End Date:</strong> {{ $project->end_date }}</p>
            <p><strong>Status:</strong> {{ $project->status }}</p>
            <p><strong>Created By:</strong> {{ $project->creator->username }}</p>
            @if($project->manager_id)
                <p><strong>Assigned Manager:</strong> {{ $project->manager->username }}</p>
            @endif
            @if($project->status === 'Completed' && $project->notes)
                <p><strong>Notes:</strong> {{ $project->notes }}</p>
            @endif
        </div>

        <!-- Manager Status Update -->
        @if(auth()->user()->role === 'Manager' && $project->manager_id === auth()->user()->id && $project->status !== 'Completed')
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Update Status</h3>
                <form action="{{ route('projects.updateStatus', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <select name="status" class="border-gray-300 rounded-md">
                        <option value="Pending" {{ $project->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ $project->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ $project->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @if($project->status !== 'Completed')
                        <textarea name="notes" placeholder="Add notes (required if completing)" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $project->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    @endif
                    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Update Status</button>
                </form>
            </div>
        @endif

        <!-- Admin Questions Section -->
        @if(auth()->user()->role === 'Admin')
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Project Questions</h3>
                @forelse($questions as $question)
                    <div class="bg-gray-100 p-3 rounded-md mb-2">
                        <p>{{ $question->question }}</p>
                        @if($question->rating)
                            <p><strong>Rating:</strong> {{ $question->rating }}/5 (Rated by {{ $question->rater->username }})</p>
                        @else
                            <form action="{{ route('questions.rate', $question) }}" method="POST" class="mt-2">
                                @csrf
                                <select name="rating" class="border-gray-300 rounded-md">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <button type="submit" class="bg-green-500 text-black px-2 py-1 rounded ml-2">Rate</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p>No questions added yet.</p>
                @endforelse

                <form action="{{ route('projects.addQuestion', $project) }}" method="POST" class="mt-4">
                    @csrf
                    <textarea name="question" placeholder="Add a question" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                    @error('question') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded mt-2">Add Question</button>
                </form>
            </div>
        @endif

        <a href="{{ route('projects.index') }}" class="text-blue-500 hover:underline">Back to Projects</a>
    </div>
@endsection
