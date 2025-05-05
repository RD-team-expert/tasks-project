@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }

        @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.02); } }
        .animate-pulse { animation: pulse 1.5s ease-in-out infinite; }

        @keyframes glow { 0%, 100% { box-shadow: 0 0 5px rgba(138, 43, 226, 0.3); } 50% { box-shadow: 0 0 15px rgba(138, 43, 226, 0.7); } }
        .animate-glow { animation: glow 2s ease-in-out infinite; }

        @keyframes ripple { to { transform: scale(2); opacity: 0; } }
        .ripple-effect { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.4); animation: ripple 0.7s linear; }

        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
        .animate-shake { animation: shake 0.3s ease-in-out; }

        #particles-js { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; }

        .input-focus:focus { box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.3); border-color: #8B2BE2; }
        .tooltip { visibility: hidden; opacity: 0; transition: all 0.3s ease; transform: translateY(10px); }
        .tooltip-parent:hover .tooltip { visibility: visible; opacity: 1; transform: translateY(0); }
        .error-message { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .button-container { display: flex; justify-content: center; gap: 1rem; }

        .dark-mode { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); color: #e0e0e0; }
        .dark-mode .bg-white { background: linear-gradient(135deg, #2a2a2a, #3a3a3a); }
        .dark-mode .text-gray-800 { color: #f0f0f0; }
        .dark-mode .text-gray-700 { color: #d0d0d0; }
        .dark-mode .text-gray-600 { color: #b0b0b0; }
        .dark-mode .border-gray-200 { border-color: #555; }
        .dark-mode .bg-gray-100 { background: #3a3a3a; }
        .dark-mode input, .dark-mode textarea, .dark-mode select { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
        .dark-mode input::placeholder, .dark-mode textarea::placeholder { color: #999; }
        .dark-mode .error-message { background: #7f1d1d; color: #fecaca; }

        .star-rating { display: inline-flex; flex-direction: row-reverse; font-size: 1.5rem; cursor: pointer; }
        .star-rating input { display: none; }
        .star-rating label { color: #ccc; transition: color 0.2s; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #facc15; }
    </style>

    <div id="particles-js"></div>

    <div x-data="{ darkMode: $store.darkMode.isDark }" :class="{ 'dark-mode': darkMode }" class="max-w-3xl mx-auto p-6 relative animate-fade-in">
        <div class="sticky top-0 z-20 bg-white shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in dark:bg-gray-800">
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">Task Details</h2>
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Back</a>
        </div>

        @if ($errors->any())
            <div class="error-message animate-fade-in">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="animate-slide-in" style="animation-delay: 0.1s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-folder mr-2 text-blue-500 dark:text-blue-400"></i>Project</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $task->project->name ?? 'N/A' }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.2s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-tasks mr-2 text-purple-500 dark:text-purple-400"></i>Task Name</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $task->name }}</dd>
                </div>
                <div class="sm:col-span-2 animate-slide-in" style="animation-delay: 0.3s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-align-left mr-2 text-gray-500 dark:text-gray-400"></i>Description</dt>
                    <dd class="mt-1 text-gray-900 whitespace-pre-line dark:text-white">{{ $task->description }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.4s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-calendar-alt mr-2 text-green-500 dark:text-green-400"></i>Start Date</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($task->start_date)->format('M d, Y') }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.5s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-calendar-alt mr-2 text-green-500 dark:text-green-400"></i>End Date</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($task->end_date)->format('M d, Y') }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.6s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-info-circle mr-2 text-yellow-500 dark:text-yellow-400"></i>Status</dt>
                    <dd class="mt-1">
                        <span class="inline-block px-2 py-1 text-sm rounded
                            {{ $task->status == 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200' :
                               ($task->status == 'In Progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200' :
                               'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200') }}">
                            {{ $task->status }}
                        </span>
                    </dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.7s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-user mr-2 text-blue-500 dark:text-blue-400"></i>Assigned To</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $task->assignedEmployee->username ?? 'N/A' }}</dd>
                </div>
                <div class="animate-slide-in" style="animation-delay: 0.8s;">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300"><i class="fas fa-user mr-2 text-blue-500 dark:text-blue-400"></i>Created By</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $task->creator->username ?? 'N/A' }}</dd>
                </div>
            </div>
        </div>

        @if($task->employee_note)
            <div class="mt-6 bg-yellow-100 p-4 rounded-lg animate-pulse dark:bg-yellow-800">
                <h3 class="font-medium text-yellow-800 dark:text-yellow-200"><i class="fas fa-sticky-note mr-2"></i>Employee Note</h3>
                <p class="mt-1 text-yellow-900 dark:text-yellow-100">{{ $task->employee_note }}</p>
            </div>
        @endif

        @if(auth()->id() === $task->created_by || auth()->user()->role === 'Admin')
            @if($task->status === 'Completed' && !$task->ratings()->exists())
                <form action="{{ route('tasks.submitRating', $task->id) }}" method="POST" class="mt-6 bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700">
                    @csrf
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 dark:text-gray-200">Rate Task Questions</h3>

                    @foreach($task->questions as $question)
                        <div class="space-y-2 mb-4">
                            <label class="block font-medium text-gray-700 dark:text-gray-300">{{ $question->text }}</label>
                            <div class="star-rating" id="rating-{{ $question->id }}">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star-{{ $question->id }}-{{ $i }}" name="ratings[{{ $question->id }}]" value="{{ $i }}" required>
                                    <label for="star-{{ $question->id }}-{{ $i }}" class="cursor-pointer">★</label>
                                @endfor
                            </div>
                            @error("ratings.{$question->id}")
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <div id="reason-section-{{ $question->id }}" class="reason-section space-y-2 hidden">
                                <label class="block font-medium text-gray-700 dark:text-gray-300">Reason for Rating Below 5</label>
                                <textarea name="reasons[{{ $question->id }}]"
                                          class="w-full border-gray-300 rounded-md shadow-sm input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                          rows="3"></textarea>
                                @error("reasons.{$question->id}")
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                    <div class="button-container">
                        <button type="submit"
                                class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                                @mousedown="addRipple($event)">
                            Submit Rating
                        </button>
                    </div>
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
                <div class="mt-6 bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 dark:text-gray-200">Question Ratings</h3>
                    <div class="space-y-4">
                        @foreach($task->ratings->last()->questionRatings as $rating)
                            <div class="border-b pb-4 last:border-b-0 dark:border-gray-600">
                                <p class="font-medium text-gray-700 dark:text-gray-300">{{ $rating->question->text }}</p>
                                <p class="text-gray-900 dark:text-white">Rating:
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
                                    <div class="mt-2 bg-red-100 p-3 rounded-lg dark:bg-red-800">
                                        <h4 class="font-medium text-red-800 dark:text-red-200">Reason for Low Rating</h4>
                                        <p class="mt-1 text-red-900 dark:text-red-100">{{ $rating->reason }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="mt-6 text-gray-500 italic dark:text-gray-400">Waiting for task completion to rate...</p>
            @endif
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        particlesJS('particles-js', {
            particles: { number: { value: 70, density: { enable: true, value_area: 900 } }, color: { value: '#8B2BE2' }, shape: { type: 'polygon', stroke: { width: 0 }, polygon: { nb_sides: 5 } }, opacity: { value: 0.7, random: true }, size: { value: 5, random: true }, line_linked: { enable: true, distance: 130, color: '#8B2BE2', opacity: 0.5, width: 1.2 }, move: { enable: true, speed: 2.5, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false } },
            interactivity: { detect_on: 'canvas', events: { onhover: { enable: true, mode: 'grab' }, onclick: { enable: true, mode: 'push' }, resize: true }, modes: { grab: { distance: 150, line_linked: { opacity: 0.7 } }, push: { particles_nb: 5 } } },
            retina_detect: true
        });

        function addRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            ripple.style.width = ripple.style.height = `${diameter}px`;
            ripple.style.left = `${event.clientX - button.getBoundingClientRect().left - radius}px`;
            ripple.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
            ripple.classList.add('ripple-effect');
            button.appendChild(ripple);
            setTimeout(() => ripple.remove(), 700);
        }
    </script>
@endsection
