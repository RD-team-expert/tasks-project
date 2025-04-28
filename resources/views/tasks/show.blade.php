@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
    <style>
        /* Fade-In Animation for Card */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Slide-In Animation for Detail Items */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        /* Pulse Animation for Employee Note */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Bounce Animation on Hover for Buttons */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .button-hover:hover {
            animation: bounce 0.5s ease-in-out;
        }

        /* Scale-Up Animation on Hover for Buttons */
        .button-scale:hover {
            transform: scale(1.05);
        }

        /* Star Rating System */
        .star-rating {
            display: inline-flex;
            flex-direction: row-reverse;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            color: #ccc;
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #facc15;
        }
    </style>

    <div class="container mx-auto max-w-2xl px-4 py-8 animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-black">Task Details</h2>
            <a href="{{ route('tasks.index') }}" class="text-sm bg-gray-100 text-gray-700 px-3 py-2 rounded hover:bg-gray-200 transition button-hover button-scale">Back</a>
        </div>

        <!-- Task Information Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-6 space-y-4 animate-fade-in">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="animate-slide-in" style="animation-delay: 0.1s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-folder mr-2 text-blue-500"></i>Project</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->project->name ?? 'N/A' }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.2s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-tasks mr-2 text-purple-500"></i>Task Name</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->name }}</dd>
                </div>
                <div class="sm:col-span-2 animate-slide-in" style="animation-delay: 0.3s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-align-left mr-2 text-gray-500"></i>Description</dt>
                    <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $task->description }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.4s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-calendar-alt mr-2 text-green-500"></i>Start Date</dt>
                    <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($task->start_date)->format('M d, Y') }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.5s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-calendar-alt mr-2 text-green-500"></i>End Date</dt>
                    <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($task->end_date)->format('M d, Y') }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.6s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-info-circle mr-2 text-yellow-500"></i>Status</dt>
                    <dd class="mt-1">
                        <span class="inline-block px-2 py-1 text-sm rounded
                            {{ $task->status == 'Completed' ? 'bg-green-100 text-green-800' :
                               ($task->status == 'In Progress' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800') }}">
                            {{ $task->status }}
                        </span>
                    </dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.7s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-user mr-2 text-blue-500"></i>Assigned To</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->assignedEmployee->username ?? 'N/A' }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.8s;">
                    <dt class="text-sm font-medium text-gray-500"><i class="fas fa-user mr-2 text-blue-500"></i>Created By</dt>
                    <dd class="mt-1 text-gray-900">{{ $task->creator->username ?? 'N/A' }}</dd>
                </div>
            </div>
        </div>

        <!-- Employee Note -->
        @if($task->employee_note)
            <div class="mb-6 bg-yellow-100 p-4 rounded-lg animate-pulse">
                <h3 class="font-medium text-yellow-800"><i class="fas fa-sticky-note mr-2"></i>Employee Note</h3>
                <p class="mt-1 text-yellow-900">{{ $task->employee_note }}</p>
            </div>
        @endif

        <!-- Rating Section -->
        @if(auth()->id() === $task->created_by || auth()->user()->role === 'Admin')
            @if($task->status === 'Completed' && !$task->ratings()->exists())
                <!-- Rating Form -->
                <form action="{{ route('tasks.submitRating', $task->id) }}" method="POST" class="space-y-6 bg-white shadow rounded-lg p-6 animate-fade-in">
                    @csrf
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Rate Task Questions</h3>

                    @foreach($task->questions as $question)
                        <div class="space-y-2">
                            <label class="block font-medium text-gray-700">{{ $question->text }}</label>
                            <div class="star-rating" id="rating-{{ $question->id }}">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star-{{ $question->id }}-{{ $i }}" name="ratings[{{ $question->id }}]" value="{{ $i }}" required>
                                    <label for="star-{{ $question->id }}-{{ $i }}" class="cursor-pointer">★</label>
                                @endfor
                            </div>
                            @error("ratings.{$question->id}")
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                            <div id="reason-section-{{ $question->id }}" class="reason-section space-y-2 hidden">
                                <label class="block font-medium text-gray-700">Reason for Rating Below 5</label>
                                <textarea name="reasons[{{ $question->id }}]"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="3"></textarea>
                                @error("reasons.{$question->id}")
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                    <button type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 button-hover button-scale">
                        Submit Rating
                    </button>
                </form>

                <script>
                    document.querySelectorAll('.star-rating').forEach(rating => {
                        const questionId = rating.id.split('-')[1];
                        const inputs = rating.querySelectorAll('input');
                        inputs.forEach(input => {
                            input.addEventListener('change', () => {
                                const ratingValue = parseInt(input.value);
                                const reasonSection = document.getElementById(`reason-section-${questionId}`);
                                reasonSection.classList.toggle('hidden', ratingValue >= 5);
                            });
                        });
                    });
                </script>

            @elseif($task->ratings()->exists())
                <!-- Ratings Display -->
                <div class="bg-white shadow rounded-lg p-6 animate-fade-in">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Question Ratings</h3>
                    <div class="space-y-4">
                        @foreach($task->ratings->last()->questionRatings as $rating)
                            <div class="border-b pb-4 last:border-b-0">
                                <p class="font-medium text-gray-700">{{ $rating->question->text }}</p>
                                <p class="text-gray-900">Rating:
                                    <span class="font-bold">{{ $rating->rating }}/5</span>
                                    <span class="text-yellow-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </span>
                                </p>
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
