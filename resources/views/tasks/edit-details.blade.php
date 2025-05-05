@extends('layouts.app')

@section('title', 'Update Task Details')

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
    </style>

    <div id="particles-js"></div>

    <div x-data="{ darkMode: $store.darkMode.isDark }" :class="{ 'dark-mode': darkMode }" class="max-w-3xl mx-auto p-6 relative animate-fade-in">
        <div class="sticky top-0 z-20 bg-white shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in dark:bg-gray-800">
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">Task Details: {{ $task->name }}</h2>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.1s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Name:</strong> {{ $task->name }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.2s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Project:</strong> {{ $task->project?->name ?? 'N/A' }}
                </p>
                <p class="sm:col-span-2 text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.3s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Description:</strong> {{ $task->description ?? 'No description' }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.4s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Start Date:</strong> {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('M d, Y') : 'Not set' }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.5s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">End Date:</strong> {{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('M d, Y') : 'Not set' }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.6s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Created By:</strong> {{ $task->creator?->username ?? 'N/A' }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.7s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Assigned To:</strong> {{ $task->assignedEmployee?->username ?? 'N/A' }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.8s;">
                    <strong class="font-semibold text-gray-800 dark:text-gray-200">Current Status:</strong>
                    <span class="inline-block px-2 py-1 text-sm rounded
                        {{ $task->status == 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200' :
                           ($task->status == 'In Progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200' :
                           'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200') }}">
                        {{ $task->status }}
                    </span>
                </p>
            </div>
        </div>

        <form x-data="{ status: '{{ old('status', $task->status) }}' }" action="{{ route('tasks.updateDetails', $task) }}" method="POST" class="mt-6 bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700">
            @csrf
            @method('PATCH')

            <div class="mb-4 relative tooltip-parent">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                    Update Status <span class="text-red-500">*</span>
                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the current task status</span>
                </label>
                <select
                    id="status"
                    name="status"
                    x-model="status"
                    required
                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    :class="{ 'border-red-500 animate-shake': $refs.form && $refs.form.status && !$refs.form.status.value }"
                >
                    <option value="Not Started" {{ old('status', $task->status) == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                    <option value="In Progress" {{ old('status', $task->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status', $task->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="status === 'Completed'" class="mb-4 relative tooltip-parent animate-slide-in" id="note-field">
                <label for="note" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                    Completion Note <span class="text-red-500">*</span>
                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Provide details about task completion</span>
                </label>
                <textarea
                    name="note"
                    id="note"
                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    placeholder="Enter completion notes..."
                    required
                    x-bind:required="status === 'Completed'"
                >{{ old('note', $task->employee_note) }}</textarea>
                @error('note')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="button-container">
                <button
                    type="submit"
                    class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                    @mousedown="addRipple($event)"
                >
                    Update Task
                </button>
            </div>
        </form>
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
