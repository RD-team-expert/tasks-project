@extends('layouts.app')

@section('title', 'Question Details')

@section('content')
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.02); } }
        .animate-pulse { animation: pulse 1.5s ease-in-out infinite; }

        @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }

        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
        .button-hover:hover { animation: bounce 0.5s ease-in-out; }

        @keyframes ripple { to { transform: scale(2); opacity: 0; } }
        .ripple-effect { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.4); animation: ripple 0.7s linear; }

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
    </style>

    <div id="particles-js"></div>

    <div x-data="{ darkMode: $store.darkMode.isDark }" :class="{ 'dark-mode': darkMode }" class="max-w-4xl mx-auto p-6 relative">
        <div class="sticky top-0 z-20 bg-white shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in dark:bg-gray-800">
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">Question Details</h2>
            <div class="button-container">
                <a href="{{ route('questions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition button-hover dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Back to Questions</a>
                @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager')
                    <a href="{{ route('questions.edit', $question->id) }}" class="px-4 py-2 bg-[#FFB107] text-white rounded hover:bg-[#FFA000] transition button-hover dark:bg-yellow-600 dark:hover:bg-yellow-700" @mousedown="addRipple($event)">Edit</a>
                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-[#DC3545] text-white rounded hover:bg-[#C82333] transition button-hover dark:bg-red-600 dark:hover:bg-red-700" @mousedown="addRipple($event)" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700 animate-fade-in">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 animate-pulse dark:text-gray-200">{{ $question->text }}</h3>
            <div class="space-y-4">
                <p class="text-gray-600 animate-slide-in dark:text-gray-300" style="animation-delay: 0.1s;">
                    <i class="fas fa-user mr-2 text-blue-500 dark:text-blue-400"></i>
                    <strong>Created by:</strong> {{ $question->creator->username ?? 'N/A' }}
                </p>
                <p class="text-gray-600 animate-slide-in dark:text-gray-300" style="animation-delay: 0.2s;">
                    <i class="fas fa-calendar-alt mr-2 text-green-500 dark:text-green-400"></i>
                    <strong>Created on:</strong> {{ $question->created_at->format('M d, Y') }}
                </p>
                <p class="text-gray-600 animate-slide-in dark:text-gray-300" style="animation-delay: 0.3s;">
                    <i class="fas fa-tasks mr-2 text-purple-500 dark:text-purple-400"></i>
                    <strong>Associated Tasks:</strong>
                @if($question->tasks->isNotEmpty())
                    <ul class="list-disc list-inside mt-1">
                        @foreach($question->tasks as $task)
                            <li>
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-[#8B2BE2] hover:underline dark:text-purple-400">
                                    {{ $task->name }}
                                </a>
                                (Project: {{ $task->project->name ?? 'N/A' }})
                            </li>
                        @endforeach
                    </ul>
                @else
                    None
                    @endif
                    </p>
            </div>
        </div>
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
