@extends('layouts.app')

@section('title', 'Edit Project')

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

        #particles-js { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; }

        .input-focus:focus { box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.3); border-color: #8B2BE2; }
        .tooltip { visibility: hidden; opacity: 0; transition: all 0.3s ease; transform: translateY(10px); }
        .tooltip-parent:hover .tooltip { visibility: visible; opacity: 1; transform: translateY(0); }
        .error-message { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .button-container { display: flex; justify-content: space-between; gap: 1rem; }
        .progress-bar { background: #e5e7eb; }
        .progress-bar-fill { background: #8B2BE2; }
        .review-section { background: #f3f4f6; }

        .dark-mode { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); color: #e0e0e0; }
        .dark-mode .bg-white { background: linear-gradient(135deg, #2a2a2a, #3a3a3a); }
        .dark-mode .text-gray-800 { color: #f0f0f0; }
        .dark-mode .text-gray-700 { color: #d0d0d0; }
        .dark-mode .text-gray-600 { color: #b0b0b0; }
        .dark-mode .text-gray-500 { color: #9ca3af; }
        .dark-mode input, .dark-mode textarea, .dark-mode select { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
        .dark-mode input::placeholder, .dark-mode textarea::placeholder { color: #999; }
        .dark-mode .error-message { background: #7f1d1d; color: #fecaca; }
        .dark-mode .progress-bar { background: #4b5563; }
        .dark-mode .progress-bar-fill { background: #7A26C9; }
        .dark-mode .review-section { background: #3a3a3a; }
    </style>

    <div id="particles-js"></div>

    <div x-data="{ darkMode: $store.darkMode.isDark }" :class="{ 'dark-mode': darkMode }" class="max-w-3xl mx-auto p-6 relative animate-fade-in">
        <div class="sticky top-0 z-20 bg-white shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in dark:bg-gray-800">
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">Edit Project: {{ $project->name ?? 'Untitled Project' }}</h2>
            <a href="{{ route('projects.show', $project->id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Back to Project Details</a>
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

        <div x-data="projectForm()" class="bg-white p-8 rounded-lg shadow-lg animate-glow dark:bg-gray-700">
            <p class="text-gray-600 mb-8 dark:text-gray-300 animate-slide-in">Update the project details</p>

            <!-- Progress Bar -->
            <div class="mb-8 animate-slide-in">
                <div class="flex justify-between mb-2">
                    <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold dark:text-purple-400': step >= 1 }">1. Info</div>
                    <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold dark:text-purple-400': step >= 2 }">2. Schedule</div>
                    <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold dark:text-purple-400': step >= 3 }">3. Review</div>
                </div>
                <div class="w-full progress-bar rounded-full h-2">
                    <div class="progress-bar-fill h-2 rounded-full transition-all duration-300" :style="{ width: (step / 3) * 100 + '%' }"></div>
                </div>
            </div>

            <form action="{{ route('projects.update', $project->id) }}" method="POST" x-ref="form">
                @csrf
                @method('PATCH')

                <!-- Step 1: Basic Info -->
                <div x-show="step === 1" class="space-y-6">
                    <div class="relative tooltip-parent animate-slide-in">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                            Project Name <span class="text-red-500">*</span>
                            <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Enter the project name</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            x-model="form.name"
                            required
                            class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative tooltip-parent animate-slide-in" style="animation-delay: 0.1s;">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                            Description
                            <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Provide a project description</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            x-model="form.description"
                            rows="3"
                            class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >{{ old('description', $project->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="button-container animate-slide-in" style="animation-delay: 0.2s;">
                        <div class="relative tooltip-parent">
                            <button
                                type="button"
                                @click="nextStep"
                                class="px-6 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                                @mousedown="addRipple($event)"
                            >
                                Next
                            </button>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Proceed to schedule</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Schedule -->
                <div x-show="step === 2" class="space-y-6">
                    <div class="relative tooltip-parent animate-slide-in">
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                            Start Date <span class="text-red-500">*</span>
                            <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the project start date</span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            x-model="form.start_date"
                            required
                            class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                        @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative tooltip-parent animate-slide-in" style="animation-delay: 0.1s;">
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                            End Date <span class="text-red-500">*</span>
                            <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the project end date</span>
                        </label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            x-model="form.end_date"
                            required
                            class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                        @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(auth()->user()->role === 'Admin')
                        <div class="relative tooltip-parent animate-slide-in" style="animation-delay: 0.2s;">
                            <label for="manager_id" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                                Manager
                                <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Assign a manager to the project</span>
                            </label>
                            <select
                                id="manager_id"
                                name="manager_id"
                                x-model="form.manager_id"
                                class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                            >
                                <option value="">Select Manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id', $project->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->username }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="button-container animate-slide-in" style="animation-delay: 0.3s;">
                        <div class="relative tooltip-parent">
                            <button
                                type="button"
                                @click="prevStep"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                                @mousedown="addRipple($event)"
                            >
                                Previous
                            </button>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Return to basic info</span>
                        </div>
                        <div class="relative tooltip-parent">
                            <button
                                type="button"
                                @click="nextStep"
                                class="px-6 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                                @mousedown="addRipple($event)"
                            >
                                Next
                            </button>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Proceed to review</span>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Review -->
                <div x-show="step === 3" class="space-y-6">
                    <div class="review-section p-4 rounded space-y-2 animate-slide-in">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Review Your Changes</h3>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> <span x-text="form.name || 'N/A'"></span></p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Description:</strong> <span x-text="form.description || 'N/A'"></span></p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Start Date:</strong> <span x-text="form.start_date || 'Not set'"></span></p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>End Date:</strong> <span x-text="form.end_date || 'Not set'"></span></p>
                        <template x-if="form.manager_id">
                            <p class="text-gray-700 dark:text-gray-300"><strong>Manager:</strong> <span x-text="getManagerName(form.manager_id)"></span></p>
                        </template>
                    </div>

                    <div class="button-container animate-slide-in" style="animation-delay: 0.1s;">
                        <div class="relative tooltip-parent">
                            <button
                                type="button"
                                @click="prevStep"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                                @mousedown="addRipple($event)"
                            >
                                Previous
                            </button>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Return to schedule</span>
                        </div>
                        <div class="relative tooltip-parent">
                            <button
                                type="submit"
                                class="px-6 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                                @mousedown="addRipple($event)"
                            >
                                Confirm and Update
                            </button>
                            <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Submit project updates</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: { value: 70, density: { enable: true, value_area: 900 } },
                color: { value: '#8B2BE2' },
                shape: { type: 'polygon', stroke: { width: 0 }, polygon: { nb_sides: 5 } },
                opacity: { value: 0.7, random: true },
                size: { value: 5, random: true },
                line_linked: { enable: true, distance: 130, color: '#8B2BE2', opacity: 0.5, width: 1.2 },
                move: { enable: true, speed: 2.5, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'grab' }, onclick: { enable: true, mode: 'push' }, resize: true },
                modes: { grab: { distance: 150, line_linked: { opacity: 0.7 } }, push: { particles_nb: 5 } }
            },
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

        function projectForm() {
            return {
                step: 1,
                form: {
                    name: '{{ old('name', $project->name) }}',
                    description: '{{ old('description', $project->description) }}',
                    start_date: '{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}',
                    end_date: '{{ old('end_date', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}',
                    manager_id: '{{ old('manager_id', $project->manager_id) }}',
                },
                managers: @json($managers),
                nextStep() { if (this.step < 3) this.step++; },
                prevStep() { if (this.step > 1) this.step--; },
                getManagerName(id) {
                    const manager = this.managers.find(m => m.id == id);
                    return manager ? manager.username : 'Not assigned';
                }
            }
        }
    </script>
@endsection
