@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-2xl px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Task Details</h2>

        <!-- Task Information Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <dl class="grid grid-cols-1 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Project</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->project->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->name }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->description }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->start_date }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->end_date }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->status }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->assignedEmployee->username }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->creator->username }}</dd>
                </div>
            </dl>
        </div>

        <!-- Employee Note Section -->
        @if($task->employee_note)
            <div class="mb-6 bg-yellow-100 p-4 rounded-lg">
                <h3 class="font-medium text-yellow-800">Employee Note</h3>
                <p class="mt-1 text-yellow-900">{{ $task->employee_note }}</p>
            </div>
        @endif

        <!-- Rating Section (Visible to Task Creator or Admin) -->
        @if(auth()->id() === $task->created_by || auth()->user()->role === 'Admin')
            @if($task->status === 'Completed' && !$task->ratings()->exists())
                <!-- Rating Form -->
                <form action="{{ route('tasks.submitRating', $task->id) }}" method="POST" class="space-y-6 bg-white shadow rounded-lg p-6">
                    @csrf
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Rate Task Questions</h3>

                    @foreach($task->questions as $question)
                        <div class="space-y-2">
                            <label class="block font-medium text-gray-700">{{ $question->text }}</label>
                            <select name="ratings[{{ $question->id }}]" class="rating-field w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error("ratings.{$question->id}")
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Reason Field for this Question (Hidden by Default) -->
                            <div id="reason-section-{{ $question->id }}" class="reason-section space-y-2 hidden">
                                <label class="block font-medium text-gray-700">Reason for Rating Below 5</label>
                                <textarea name="reasons[{{ $question->id }}]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="3"></textarea>
                                @error("reasons.{$question->id}")
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Submit Rating
                    </button>
                </form>

                <!-- JavaScript for Dynamic Reason Fields -->
                <script>
                    document.querySelectorAll('.rating-field').forEach(select => {
                        select.addEventListener('change', () => {
                            const questionId = select.name.match(/\d+/)[0];
                            const rating = parseInt(select.value);
                            const reasonSection = document.getElementById(`reason-section-${questionId}`);
                            reasonSection.classList.toggle('hidden', rating >= 5 || !rating);
                        });
                    });
                </script>

            @elseif($task->ratings()->exists())
                <!-- Display Existing Ratings -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Question Ratings</h3>
                    <div class="space-y-4">
                        @foreach($task->ratings->last()->questionRatings as $rating)
                            <div class="border-b pb-4 last:border-b-0">
                                <p class="font-medium text-gray-700">{{ $rating->question->text }}</p>
                                <p class="text-gray-900">Rating: <span class="font-bold">{{ $rating->rating }}/5</span></p>
                                @if($rating->reason && $rating->rating < 5)
                                    <div class="mt-2 bg-red-100 p-3 rounded-lg">
                                        <h4 class="font-medium text-red-800">Reason for Low Rating</h4>
                                        <p class="mt-1 text-red-900">{{ $rating->reason }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-gray-500 italic">Waiting for task completion to rate...</p>
            @endif
        @endif
    </div>
@endsection
