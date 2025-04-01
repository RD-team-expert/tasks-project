@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Task Details: {{ $task->name }}</h2>

        <!-- Task Details Section -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <p class="text-gray-700"><strong class="font-semibold">Name:</strong> {{ $task->name }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">Description:</strong> {{ $task->description ?? 'No description' }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">Project:</strong> {{ $task->project?->name ?? 'N/A' }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">Start Date:</strong> {{ $task->start_date ?? 'Not set' }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">End Date:</strong> {{ $task->end_date ?? 'Not set' }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">Created By:</strong> {{ $task->creator?->username ?? 'N/A' }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">Assigned To:</strong> {{ $task->assignedEmployee?->username ?? 'N/A' }}</p>
            <p class="text-gray-700 mt-2"><strong class="font-semibold">Current Status:</strong> {{ $task->status }}</p>
        </div>

        <!-- Update Form -->
        <form action="{{ route('tasks.updateDetails', $task) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PATCH')

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-semibold mb-2">Update Status *</label>
                <select id="status" name="status" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Not Started" {{ old('status', $task->status) == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                    <option value="In Progress" {{ old('status', $task->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status', $task->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Note Field -->
            <div id="note-field" class="mb-4 {{ $task->status === 'Completed' ? '' : 'hidden' }}">
                <label for="note" class="block text-gray-700 font-semibold mb-2">Completion Note {{ $task->status === 'Completed' ? '*' : '' }}</label>
                <textarea name="note" id="note" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Enter completion notes..." {{ $task->status === 'Completed' ? 'required' : '' }}>{{ old('note', $task->employee_note) }}</textarea>
                @error('note')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition duration-150">
                Update Task
            </button>
        </form>
    </div>

    <script>
        const statusSelect = document.getElementById('status');
        const noteField = document.getElementById('note-field');
        const noteTextarea = document.getElementById('note');

        function toggleNoteField() {
            const isCompleted = statusSelect.value === 'Completed';
            noteField.classList.toggle('hidden', !isCompleted);
            noteTextarea.required = isCompleted;
        }

        statusSelect.addEventListener('change', toggleNoteField);
        document.addEventListener('DOMContentLoaded', toggleNoteField);
    </script>
@endsection
