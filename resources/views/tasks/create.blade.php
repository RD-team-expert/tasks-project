@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
    <!-- Particle Background -->
    <div id="particles-js"></div>

    <style>
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse { animation: pulse 1.5s ease-in-out infinite; }

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
        .animate-shake { animation: shake 0.3s ease-in-out; }

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
        .dark-mode { background-color: #1a1a1a; color: #e0e0e0; }
        .dark-mode .bg-white { background: linear-gradient(135deg, #333, #444); }
        .dark-mode .bg-gray-800 { background: #2a2a2a; }
        .dark-mode .text-gray-800 { color: #fff; }
        .dark-mode .text-gray-600 { color: #bbb; }
        .dark-mode .border-gray-200 { border-color: #555; }
        .dark-mode .bg-gray-100 { background: #2a2a2a; }
        .dark-mode .bg-gray-200 { background: #444; }
        .dark-mode select, .dark-mode input, .dark-mode textarea {
            background: #333;
            color: #e0e0e0;
            border-color: #555;
        }
        .dark-mode .error-message { background: #7f1d1d; color: #fecaca; }

        /* Input and Button Styles */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.3);
            border-color: #8B2BE2;
        }

        /* Error Message */
        .error-message {
            background: #fee2e2;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>

    <div x-data="taskForm()" class="max-w-4xl mx-auto p-6 relative" :class="{ 'dark-mode': darkMode }">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-20 bg-white dark:bg-gray-800 shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 animate-pulse">Create New Task</h2>
            <div class="flex items-center space-x-4">
                <button @click="darkMode = !darkMode" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Error Message -->
        <div x-show="submitError" class="error-message animate-fade-in" x-text="submitError"></div>

        <!-- Progress Sidebar (Desktop) -->
        <div class="flex">
            <div class="w-1/4 pr-6 hidden md:block">
                <div class="sticky top-20">
                    <div class="mb-4 animate-fade-in" :style="'animation-delay: ' + (index * 0.1) + 's'" x-for="(stepName, index) in ['Task Details', 'Timeline', 'Assignment', 'Review']" :key="index">
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
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <!-- Progress Bar (Mobile) -->
                    <div class="mb-6 md:hidden animate-fade-in">
                        <div class="flex justify-between mb-2 text-sm">
                            <template x-for="(label, index) in ['Details', 'Timeline', 'Assignment', 'Review']" :key="index">
                                <div class="flex-1 text-center" :class="{ 'text-[#8B2BE2] font-bold': step >= index + 1 }" x-text="label"></div>
                            </template>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-[#8B2BE2] h-2 rounded-full transition-all duration-500" :style="{ width: (step / 4) * 100 + '%' }"></div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('tasks.store') }}" method="POST" x-ref="form" @submit.prevent="submitForm">
                        @csrf

                        <!-- Step 1: Task Details -->
                        <div x-show="step === 1" class="animate-slide-in">
                            <!-- Project Selection -->
                            <div class="mb-4">
                                <label for="project_id" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Project <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="project_id"
                                    name="project_id"
                                    x-model="form.project_id"
                                    @change="validateProject"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg input-focus"
                                    :class="{ 'border-red-500 animate-shake': errors.project_id }"
                                    aria-required="true"
                                >
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p x-show="errors.project_id" class="text-red-500 text-sm mt-1" x-text="errors.project_id"></p>
                                @error('project_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Task Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Task Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    x-model="form.name"
                                    @input="validateName"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg input-focus"
                                    :class="{ 'border-red-500 animate-shake': errors.name }"
                                    aria-required="true"
                                >
                                <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                                @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Task Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    x-model="form.description"
                                    class="w-full px-4 py-2 border rounded-lg input-focus h-32 resize-y"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="relative bg-[#8B2BE2] text-white px-4 py-2 rounded hover:bg-[#7A26C9] overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Timeline -->
                        <div x-show="step === 2" class="animate-slide-in">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                        Start Date
                                    </label>
                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        x-model="form.start_date"
                                        @input="validateDates"
                                        class="w-full px-4 py-2 border rounded-lg input-focus"
                                    >
                                    @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                        End Date
                                    </label>
                                    <input
                                        type="date"
                                        id="end_date"
                                        name="end_date"
                                        x-model="form.end_date"
                                        @input="validateDates"
                                        class="w-full px-4 py-2 border rounded-lg input-focus"
                                        :class="{ 'border-red-500 animate-shake': errors.dates }"
                                    >
                                    <p x-show="errors.dates" class="text-red-500 text-sm mt-1" x-text="errors.dates"></p>
                                    @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="relative bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="relative bg-[#8B2BE2] text-white px-4 py-2 rounded hover:bg-[#7A26C9] overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Assignment -->
                        <div x-show="step === 3" class="animate-slide-in">
                            <!-- Status Selection -->
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    x-model="form.status"
                                    @change="validateStatus"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg input-focus"
                                    :class="{ 'border-red-500 animate-shake': errors.status }"
                                    aria-required="true"
                                >
                                    <option value="Not Started" {{ old('status', 'Not Started') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <p x-show="errors.status" class="text-red-500 text-sm mt-1" x-text="errors.status"></p>
                                @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assigned Employee -->
                            <div class="mb-4">
                                <label for="assigned_to" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Assign to Employee <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="assigned_to"
                                    name="assigned_to"
                                    x-model="form.assigned_to"
                                    @change="validateAssignedTo"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg input-focus"
                                    :class="{ 'border-red-500 animate-shake': errors.assigned_to }"
                                    aria-required="true"
                                >
                                    <option value="">Select Employee</option>
                                    @forelse($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->username }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No employees available</option>
                                    @endforelse
                                </select>
                                <p x-show="errors.assigned_to" class="text-red-500 text-sm mt-1" x-text="errors.assigned_to"></p>
                                @error('assigned_to')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @if($employees->isEmpty() && auth()->user()->role === 'Manager')
                                    <p class="text-yellow-600 text-sm mt-1">Warning: No employees found in your group.</p>
                                @endif
                            </div>

                            <!-- Select Existing Questions -->
                            <div class="mb-4">
                                <label for="questions" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Select Questions <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="questions"
                                    name="questions[]"
                                    x-model="form.questions"
                                    @change="validateQuestions"
                                    multiple
                                    required
                                    class="w-full px-4 py-2 border rounded-lg input-focus h-32"
                                    :class="{ 'border-red-500 animate-shake': errors.questions }"
                                    aria-required="true"
                                >
                                    @foreach($questions as $question)
                                        <option value="{{ $question->id }}" {{ in_array($question->id, old('questions', [])) ? 'selected' : '' }}>
                                            {{ $question->text }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-gray-400 dark:text-gray-300 text-sm mt-1">Hold Ctrl/Cmd to select multiple questions</p>
                                <p x-show="errors.questions" class="text-red-500 text-sm mt-1" x-text="errors.questions"></p>
                                @error('questions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Add New Question -->
                            <div class="mb-4">
                                <label for="new_question" class="block text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                    Add New Question (optional)
                                </label>
                                <input
                                    type="text"
                                    id="new_question"
                                    name="new_question"
                                    x-model="form.new_question"
                                    class="w-full px-4 py-2 border rounded-lg input-focus"
                                    placeholder="Enter a new question..."
                                >
                                @error('new_question')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="relative bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="button"
                                    @click="nextStep"
                                    :disabled="errors.status || errors.assigned_to || errors.questions || !form.status || !form.assigned_to || !form.questions.length"
                                    class="relative bg-[#8B2BE2] text-white px-4 py-2 rounded hover:bg-[#7A26C9] disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Review -->
                        <div x-show="step === 4" class="animate-slide-in">
                            <h3 class="font-semibold mb-4 text-gray-800 dark:text-gray-200">Review Your Task</h3>
                            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg mb-6">
                                <p><strong>Project:</strong> <span x-text="form.project_id ? $refs.form.querySelector(`#project_id option[value='${form.project_id}']`)?.text || 'Not selected' : 'Not selected'"></span></p>
                                <p><strong>Task Name:</strong> <span x-text="form.name || 'Not provided'"></span></p>
                                <p><strong>Description:</strong> <span x-text="form.description || 'Not provided'"></span></p>
                                <p><strong>Start Date:</strong> <span x-text="form.start_date || 'Not provided'"></span></p>
                                <p><strong>End Date:</strong> <span x-text="form.end_date || 'Not provided'"></span></p>
                                <p><strong>Status:</strong> <span x-text="form.status || 'Not selected'"></span></p>
                                <p><strong>Assigned To:</strong> <span x-text="form.assigned_to ? $refs.form.querySelector(`#assigned_to option[value='${form.assigned_to}']`)?.text || 'Not selected' : 'Not selected'"></span></p>
                                <p><strong>Questions:</strong> <span x-text="form.questions.length ? Array.from(form.questions).map(id => $refs.form.querySelector(`#questions option[value='${id}']`)?.text || '').join(', ') : 'None selected'"></span></p>
                                <p><strong>New Question:</strong> <span x-text="form.new_question || 'Not provided'"></span></p>
                            </div>
                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="relative bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 overflow-hidden"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="relative bg-[#8B2BE2] text-white px-4 py-2 rounded hover:bg-[#7A26C9] disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden"
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
        particlesJS('particles-js', {
            particles: {
                number: { value: 70, density: { enable: true, value_area: 900 } },
                color: { value: '#8B2BE2' },
                shape: { type: 'circle' },
                opacity: { value: 0.6, random: true },
                size: { value: 4, random: true },
                line_linked: { enable: true, color: '#8B2BE2', opacity: 0.4 },
                move: { enable: true, speed: 2 }
            },
            interactivity: {
                events: { onhover: { enable: true, mode: 'grab' }, resize: true },
                modes: { grab: { distance: 140, line_linked: { opacity: 0.5 } } }
            },
            retina_detect: true
        });

        function taskForm() {
            return {
                step: 1,
                darkMode: false,
                showConfetti: false,
                isSubmitting: false,
                submitError: '',
                form: {
                    project_id: '{{ old('project_id') }}',
                    name: '{{ old('name') }}',
                    description: '{{ old('description') }}',
                    start_date: '{{ old('start_date') }}',
                    end_date: '{{ old('end_date') }}',
                    status: '{{ old('status', 'Not Started') }}',
                    assigned_to: '{{ old('assigned_to') }}',
                    questions: @json(old('questions', [])),
                    new_question: '{{ old('new_question') }}'
                },
                errors: {
                    project_id: '',
                    name: '',
                    dates: '',
                    status: '',
                    assigned_to: '',
                    questions: ''
                },
                validateProject() {
                    this.errors.project_id = this.form.project_id ? '' : 'Please select a project';
                },
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Task name is required' : '';
                },
                validateDates() {
                    this.errors.dates = '';
                    if (this.form.start_date && this.form.end_date) {
                        const start = new Date(this.form.start_date);
                        const end = new Date(this.form.end_date);
                        if (end < start) {
                            this.errors.dates = 'End date must be after start date';
                        }
                    }
                },
                validateStatus() {
                    this.errors.status = this.form.status ? '' : 'Please select a status';
                },
                validateAssignedTo() {
                    this.errors.assigned_to = this.form.assigned_to ? '' : 'Please select an employee';
                },
                validateQuestions() {
                    this.errors.questions = this.form.questions.length ? '' : 'Please select at least one question';
                },
                nextStep() {
                    if (this.step === 1) {
                        this.validateProject();
                        this.validateName();
                        if (this.errors.project_id || this.errors.name) return;
                    }
                    if (this.step === 2) {
                        this.validateDates();
                        if (this.errors.dates) return;
                    }
                    if (this.step === 3) {
                        this.validateStatus();
                        this.validateAssignedTo();
                        this.validateQuestions();
                        if (this.errors.status || this.errors.assigned_to || this.errors.questions) return;
                    }
                    if (this.step < 4) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
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
                    if (this.form.questions.length > 0) {
                        this.form.questions.forEach(questionId => {
                            formData.append('questions[]', questionId);
                        });
                    } else {
                        formData.append('questions[]', ''); // Send empty array explicitly
                    }
                    if (!formData.get('new_question')) {
                        formData.delete('new_question'); // Ensure null if empty
                    }
                    if (!formData.get('start_date')) {
                        formData.delete('start_date'); // Ensure null if empty
                    }
                    if (!formData.get('end_date')) {
                        formData.delete('end_date'); // Ensure null if empty
                    }
                    if (!formData.get('description')) {
                        formData.delete('description'); // Ensure null if empty
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
                        const response = await fetch('{{ route('tasks.store') }}', {
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
                                window.location.href = '{{ route('tasks.index') }}';
                            }, 2000);
                        } else if (response.status === 422) {
                            const errors = await response.json();
                            this.submitError = Object.values(errors.errors).flat().join(', ');
                            console.error('Validation errors:', errors);
                        } else if (response.status === 419) {
                            this.submitError = 'Session expired. Please refresh the page and try again.';
                            console.error('CSRF token mismatch');
                        } else if (response.status === 403) {
                            this.submitError = 'You are not authorized to create tasks.';
                            console.error('Authorization error');
                        } else {
                            this.submitError = 'Failed to create task. Please try again.';
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
