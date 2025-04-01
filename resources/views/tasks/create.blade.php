@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Task</h2>

        <form action="{{ route('tasks.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf

            <!-- Project Selection -->
            <div class="mb-4">
                <label for="project_id" class="block text-gray-700 font-semibold mb-2">Project *</label>
                <select id="project_id" name="project_id" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Task Name -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Task Name *</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Task Description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea id="description" name="description"
                          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date -->
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 font-semibold mb-2">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                       class="w-full border rounded px @error('start_date') border-red-500 @enderror-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('start_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date -->
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 font-semibold mb-2">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('end_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Selection -->
            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-semibold mb-2">Status *</label>
                <select id="status" name="status" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Not Started" {{ old('status', 'Not Started') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assigned Employee -->
            <div class="mb-4">
                <label for="assigned_to" class="block text-gray-700 font-semibold mb-2">Assign to Employee *</label>
                <select id="assigned_to" name="assigned_to" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Employee --</option>
                    @forelse($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->username }}
                        </option>
                    @empty
                        <option value="" disabled>No employees available in your group</option>
                    @endforelse
                </select>
                @error('assigned_to')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @if($employees->isEmpty() && auth()->user()->role === 'Manager')
                    <p class="text-yellow-600 text-sm mt-1">Warning: No employees found in your group to assign tasks to.</p>
                @endif
            </div>

            <!-- Select Existing Questions -->
            <div class="mb-4">
                <label for="questions" class="block text-gray-700 font-semibold mb-2">Select Questions *</label>
                <select id="questions" name="questions[]" multiple required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 h-32">
                    @foreach($questions as $question)
                        <option value="{{ $question->id }}" {{ in_array($question->id, old('questions', [])) ? 'selected' : '' }}>
                            {{ $question->text }}
                        </option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-sm mt-1">Hold Ctrl/Cmd to select multiple questions</p>
                @error('questions')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Add New Question (optional) -->
            <div class="mb-6">
                <label for="new_question" class="block text-gray-700 font-semibold mb-2">Add New Question (optional)</label>
                <input type="text" id="new_question" name="new_question" value="{{ old('new_question') }}"
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter a new question...">
                @error('new_question')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition duration-150">
                    Create Task
                </button>
            </div>
        </form>
    </div>
@endsection
