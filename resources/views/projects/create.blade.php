@extends('layouts.app')

@section('title', 'Create New Project')

@section('content')
    <style>
        /* Custom Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 5px rgba(138, 43, 226, 0.3); }
            50% { box-shadow: 0 0 15px rgba(138, 43, 226, 0.7); }
        }
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce {
            animation: bounce 0.5s ease-in-out;
        }

        @keyframes ripple {
            to { transform: scale(2); opacity: 0; }
        }
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            animation: ripple 0.7s linear;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake {
            animation: shake 0.3s ease-in-out;
        }

        @keyframes confetti {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        /* Particle Background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        /* Dark Mode */
        .dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        .dark-mode .card {
            background: linear-gradient(135deg, #333, #444);
        }
        .dark-mode .bg-white {
            background: linear-gradient(135deg, #333, #444);
        }
        .dark-mode .text-gray-800 {
            color: #fff;
        }
        .dark-mode .text-gray-600 {
            color: #bbb;
        }
        .dark-mode .border-gray-200 {
            border-color: #555;
        }
        .dark-mode .bg-gray-100 {
            background: #2a2a2a;
        }

        /* Input and Button Styles */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.3);
            border-color: #8B2BE2;
        }

        /* Drag and Drop */
        .drag-over {
            border: 2px dashed #8B2BE2;
            background: rgba(138, 43, 226, 0.1);
        }

        /* Tooltip */
        .tooltip {
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease;
            transform: translateY(10px);
        }
        .tooltip-parent:hover .tooltip {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        /* Error Message */
        .error-message {
            background: #fee2e2;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .dark-mode .error-message {
            background: #7f1d1d;
            color: #fecaca;
        }
    </style>

    <!-- Particle Background -->
    <div id="particles-js"></div>

    <div x-data="projectForm()" class="max-w-4xl mx-auto p-6 relative" :class="{ 'dark-mode': darkMode }">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-20 bg-white dark:bg-gray-800 shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 animate-pulse">Create New Project</h2>
            <div class="flex items-center space-x-4">
                <!-- Help Button -->
                <button @click="showTour = !showTour" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                    <i class="fas fa-question-circle text-2xl animate-bounce"></i>
                </button>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Guided Tour -->
        <div x-show="showTour" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg max-w-md">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">Quick Guide</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">Follow these steps to create your project:</p>
                <ul class="list-disc pl-5 text-gray-600 dark:text-gray-300">
                    <li>Fill in the project name and description.</li>
                    <li>Set the start and end dates.</li>
                    <li>Drag and drop team members to assign them.</li>
                    <li>Review and submit!</li>
                </ul>
                <button @click="showTour = false" class="mt-4 px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition">Got it!</button>
            </div>
        </div>

        <!-- Error Message -->
        <div x-show="submitError" class="error-message animate-fade-in" x-text="submitError"></div>

        <!-- Progress Sidebar -->
        <div class="flex">
            <div class="w-1/4 pr-6 hidden md:block">
                <div class="sticky top-20">
                    <div class="mb-4 animate-fade-in" :style="'animation-delay: ' + (index * 0.1) + 's'" x-for="(stepName, index) in ['Project Details', 'Timeline', 'Team', 'Review']" :key="index">
                        <div class="flex items-center mb-2 cursor-pointer" @click="step = index + 1" :class="{ 'text-[#8B2BE2] font-bold': step === index + 1 }">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full border-2" :class="step >= index + 1 ? 'border-[#8B2BE2] bg-[#8B2BE2] text-white' : 'border-gray-300 text-gray-500'">
                                <i x-show="step > index + 1" class="fas fa-check"></i>
                                <span x-show="step <= index + 1" x-text="index + 1"></span>
                            </span>
                            <span class="ml-2" x-text="stepName"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="w-full md:w-3/4">
                <div class="card bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate-glow">
                    <!-- Progress Bar (Mobile) -->
                    <div class="mb-8 md:hidden">
                        <div class="flex justify-between mb-2 text-sm">
                            <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold': step >= 1 }">Details</div>
                            <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold': step >= 2 }">Timeline</div>
                            <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold': step >= 3 }">Team</div>
                            <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold': step >= 4 }">Review</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#8B2BE2] h-2 rounded-full transition-all duration-500" :style="{ width: (step / 4) * 100 + '%' }"></div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('projects.store') }}" method="POST" x-ref="form" @submit.prevent="submitForm">
                        @csrf

                        <!-- Step 1: Project Details -->
                        <div x-show="step === 1" class="animate-slide-in">
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 relative tooltip-parent">
                                    Project Name <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2">A unique name to identify your project</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    x-model="form.name"
                                    @input="validateName"
                                    placeholder="e.g., Mobile App Redesign"
                                    class="w-full px-4 py-2 border rounded-lg input-focus transition"
                                    :class="{ 'border-red-500 animate-shake': errors.name }"
                                    required
                                    aria-required="true"
                                >
                                <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                            </div>
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 relative tooltip-parent">
                                    Description
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2">Outline the project’s goals</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    x-model="form.description"
                                    placeholder="e.g., Develop a new feature"
                                    class="w-full px-4 py-2 border rounded-lg input-focus transition h-32 resize-none"
                                ></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="relative px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Timeline -->
                        <div x-show="step === 2" class="animate-slide-in">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 relative tooltip-parent">
                                        Start Date
                                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer"></i>
                                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2">When the project begins</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        x-model="form.start_date"
                                        @input="validateDates"
                                        class="w-full px-4 py-2 border rounded-lg input-focus transition"
                                    >
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 relative tooltip-parent">
                                        End Date <span class="text-red-500">*</span>
                                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer"></i>
                                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2">Must be later than start date</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="end_date"
                                        name="end_date"
                                        x-model="form.end_date"
                                        @input="validateDates"
                                        class="w-full px-4 py-2 border rounded-lg input-focus transition"
                                        :class="{ 'border-red-500 animate-shake': errors.dates }"
                                        required
                                        aria-required="true"
                                    >
                                    <p x-show="errors.dates" class="text-red-500 text-sm mt-1" x-text="errors.dates"></p>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="relative px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="button"
                                    @click="nextStep"
                                    :disabled="errors.dates || !form.end_date"
                                    class="relative px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Team -->
                        <div x-show="step === 3" class="animate-slide-in">
                            @if(auth()->user()->role === 'Admin')
                                <div class="mb-6">
                                    <label for="manager_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 relative tooltip-parent">
                                        Project Manager
                                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer"></i>
                                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2">Select a manager (optional)</span>
                                    </label>
                                    <select
                                        id="manager_id"
                                        name="manager_id"
                                        x-model="form.manager_id"
                                        class="w-full px-4 py-2 border rounded-lg input-focus transition"
                                    >
                                        <option value="">Select a manager (optional)</option>
                                        @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 relative tooltip-parent">
                                    Team Members
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2">Drag users to assign them</span>
                                </label>
                                <div class="flex space-x-4">
                                    <!-- Available Users -->
                                    <div class="w-1/2 p-4 border rounded-lg" @dragover.prevent @drop="dropUser($event, false)">
                                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Available</h4>
                                        <template x-for="user in availableUsers" :key="user.id">
                                            <div
                                                class="p-2 bg-gray-100 dark:bg-gray-600 rounded mb-2 cursor-move"
                                                draggable="true"
                                                @dragstart="dragUser($event, user)"
                                                x-text="user.username"
                                            ></div>
                                        </template>
                                    </div>
                                    <!-- Selected Team -->
                                    <div class="w-1/2 p-4 border rounded-lg" :class="{ 'drag-over': isDragging }" @dragover.prevent @drop="dropUser($event, true)">
                                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Team</h4>
                                        <template x-for="member in form.team_members" :key="member.id">
                                            <div
                                                class="p-2 bg-[#8B2BE2] text-white rounded mb-2 cursor-move"
                                                draggable="true"
                                                @dragstart="dragUser($event, member)"
                                                x-text="member.username"
                                            ></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="relative px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="relative px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Review -->
                        <div x-show="step === 4" class="animate-slide-in">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Review Your Project</h3>
                            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <p><strong>Project Name:</strong> <span x-text="form.name || 'Not provided'"></span></p>
                                <p><strong>Description:</strong> <span x-text="form.description || 'Not provided'"></span></p>
                                <p><strong>Start Date:</strong> <span x-text="form.start_date || 'Not provided'"></span></p>
                                <p><strong>End Date:</strong> <span x-text="form.end_date || 'Not provided'"></span></p>
                                <p><strong>Project Manager:</strong>
                                    <span x-text="form.manager_id ? availableUsers.find(u => u.id == form.manager_id)?.username || 'Not assigned' : 'Not assigned'"></span>
                                </p>
                                <p><strong>Team Members:</strong>
                                    <span x-text="form.team_members.length ? form.team_members.map(m => m.username).join(', ') : 'None selected'"></span>
                                </p>
                            </div>
                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="relative px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="relative px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed"
                                    @mousedown="addRipple($event)"
                                >
                                    <span x-show="!isSubmitting">Confirm and Submit</span>
                                    <span x-show="isSubmitting" class="flex items-center">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Submitting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Confetti Celebration -->
        <div x-show="showConfetti" class="fixed inset-0 pointer-events-none z-50">
            <template x-for="i in 50">
                <div
                    class="absolute w-2 h-2 rounded"
                    :style="{
                        left: Math.random() * 100 + 'vw',
                        top: '-10px',
                        backgroundColor: ['#8B2BE2', '#FF6F61', '#FFD700', '#40C4FF'][Math.floor(Math.random() * 4)],
                        animation: 'confetti ' + (1 + Math.random()) + 's ease forwards',
                        animationDelay: Math.random() * 0.5 + 's'
                    }"
                ></div>
            </template>
        </div>
    </div>

    <!-- Include Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Initialize Particles.js with purple theme
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

        function projectForm() {
            return {
                step: 1,
                darkMode: false,
                showTour: true,
                showConfetti: false,
                isSubmitting: false,
                submitError: '',
                form: {
                    name: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    manager_id: '',
                    team_members: []
                },
                availableUsers: @json($users->map(function($user) {
                    return ['id' => $user->id, 'username' => $user->username];
                })->toArray()),
                errors: {
                    name: '',
                    dates: ''
                },
                draggedUser: null,
                isDragging: false,
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Project name is required' : '';
                },
                validateDates() {
                    this.errors.dates = '';
                    if (!this.form.end_date) {
                        this.errors.dates = 'End date is required';
                        return;
                    }
                    if (this.form.start_date && this.form.end_date) {
                        const start = new Date(this.form.start_date);
                        const end = new Date(this.form.end_date);
                        if (end < start) {
                            this.errors.dates = 'End date must be after start date';
                        }
                    }
                },
                nextStep() {
                    if (this.step === 1) {
                        this.validateName();
                        if (this.errors.name) return;
                    }
                    if (this.step === 2) {
                        this.validateDates();
                        if (this.errors.dates || !this.form.end_date) return;
                    }
                    if (this.step < 4) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                },
                dragUser(event, user) {
                    this.draggedUser = user;
                    event.dataTransfer.setData('text/plain', JSON.stringify(user));
                },
                dropUser(event, isTeam) {
                    event.preventDefault();
                    this.isDragging = false;
                    const user = this.draggedUser;
                    if (!user) return;
                    if (isTeam) {
                        if (!this.form.team_members.find(m => m.id === user.id)) {
                            this.form.team_members.push(user);
                            this.availableUsers = this.availableUsers.filter(u => u.id !== user.id);
                        }
                    } else {
                        if (!this.availableUsers.find(u => u.id === user.id)) {
                            this.availableUsers.push(user);
                            this.form.team_members = this.form.team_members.filter(m => m.id !== user.id);
                        }
                    }
                    this.draggedUser = null;
                },
                addRipple(event) {
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
                },
                async submitForm() {
                    this.isSubmitting = true;
                    this.submitError = '';

                    const formData = new FormData(this.$refs.form);
                    if (this.form.team_members.length > 0) {
                        this.form.team_members.forEach(member => {
                            formData.append('employees[]', member.id);
                        });
                    } else {
                        formData.append('employees[]', ''); // Send empty array explicitly
                    }
                    if (!formData.get('manager_id')) {
                        formData.delete('manager_id'); // Ensure null if empty
                    }

                    // Get CSRF token from the form's _token input
                    const csrfToken = this.$refs.form.querySelector('input[name="_token"]').value;
                    if (!csrfToken) {
                        this.submitError = 'CSRF token is missing. Please refresh the page and try again.';
                        this.isSubmitting = false;
                        console.error('CSRF token not found in form');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('projects.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (response.ok) {
                            this.showConfetti = true;
                            setTimeout(() => {
                                this.showConfetti = false;
                                window.location.href = '{{ route('projects.index') }}';
                            }, 2000);
                        } else if (response.status === 422) {
                            const errors = await response.json();
                            this.submitError = Object.values(errors.errors).flat().join(', ');
                            console.error('Validation errors:', errors);
                        } else if (response.status === 419) {
                            this.submitError = 'Session expired. Please refresh the page and try again.';
                            console.error('CSRF token mismatch');
                        } else if (response.status === 403) {
                            this.submitError = 'You are not authorized to create projects.';
                            console.error('Authorization error');
                        } else {
                            this.submitError = 'Failed to create project. Please try again.';
                            console.error('Server error:', response.status, await response.text());
                        }
                    } catch (error) {
                        this.submitError = 'An error occurred. Please check your connection and try again.';
                        console.error('Fetch error:', error);
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>
@endsection
