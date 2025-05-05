@extends('layouts.app')

@section('title', 'Edit Task')

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

        @keyframes confetti { 0% { transform: translateY(0) rotate(0deg); opacity: 1; } 100% { transform: translateY(100vh) rotate(720deg); opacity: 0; } }

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

    <div x-data="taskForm()" :class="{ 'dark-mode': $store.darkMode.isDark }" class="max-w-4xl mx-auto p-6 relative">
        <div class="sticky top-0 z-20 bg-white shadow-md p-4 rounded-lg mb-8 flex justify-center items-center animate-slide-in dark:bg-gray-800">
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">Edit Task</h2>
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

        <div class="flex justify-center">
            <div class="w-full md:w-3/4">
                <div class="bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700">
                    <div class="mb-8 md:hidden">
                        <div class="flex justify-between mb-2 text-sm">
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 1 }">Basic Info</div>
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 2 }">Scheduling</div>
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 3 }">Review</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-600">
                            <div class="bg-[#8B2BE2] h-2 rounded-full transition-all duration-500" :style="{ width: (step / 3) * 100 + '%' }"></div>
                        </div>
                    </div>

                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" x-ref="form" @submit="submitForm">
                        @csrf
                        @method('PUT')

                        <div x-show="step === 1" class="space-y-6 animate-slide-in">
                            <div>
                                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Project <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the associated project</span>
                                </label>
                                <select
                                    name="project_id"
                                    id="project_id"
                                    x-model="form.project_id"
                                    @change="validateProject"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500 animate-shake': errors.project_id }"
                                    required
                                >
                                    <option value="" disabled>Select a project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p x-show="errors.project_id" class="text-red-500 text-sm mt-1" x-text="errors.project_id"></p>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Task Name <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Enter a clear and concise task name</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    x-model="form.name"
                                    @input="validateName"
                                    placeholder="e.g., Develop Login Page"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500 animate-shake': errors.name }"
                                    required
                                >
                                <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Description
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Provide detailed task information</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    x-model="form.description"
                                    rows="4"
                                    placeholder="e.g., Implement user authentication with JWT..."
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                >{{ old('description', $task->description) }}</textarea>
                            </div>

                            <div class="button-container">
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <div x-show="step === 2" class="space-y-6 animate-slide-in">
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Start Date
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the task start date</span>
                                </label>
                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    x-model="form.start_date"
                                    @change="validateDates"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500 animate-shake': errors.dates }"
                                >
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    End Date
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the task end date</span>
                                </label>
                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                    x-model="form.end_date"
                                    @change="validateDates"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500 animate-shake': errors.dates }"
                                >
                                <p x-show="errors.dates" class="text-red-500 text-sm mt-1" x-text="errors.dates"></p>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Status
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the current task status</span>
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    x-model="form.status"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                >
                                    @foreach(['Not Started', 'In Progress', 'Completed'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $task->status) === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Assign To <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select the employee to assign the task to</span>
                                </label>
                                <select
                                    id="assigned_to"
                                    name="assigned_to"
                                    x-model="form.assigned_to"
                                    @change="validateAssignedTo"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500 animate-shake': errors.assigned_to }"
                                    required
                                >
                                    <option value="" disabled>Select an employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('assigned_to', $task->assigned_to) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->username }}
                                        </option>
                                    @endforeach
                                </select>
                                <p x-show="errors.assigned_to" class="text-red-500 text-sm mt-1" x-text="errors.assigned_to"></p>
                            </div>

                            <div class="button-container">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                                    @mousedown="addRipple($event)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <div x-show="step === 3" class="space-y-6 animate-slide-in">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Questions (max 3)
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select up to 3 evaluation questions</span>
                                </label>
                                <div class="space-y-2">
                                    @foreach($questions as $question)
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                id="question_{{ $question->id }}"
                                                name="questions[]"
                                                value="{{ $question->id }}"
                                                x-model="form.questions"
                                                @change="validateQuestions"
                                                class="h-4 w-4 text-[#8B2BE2] focus:ring-[#8B2BE2] border-gray-300 rounded dark:border-gray-600"
                                                {{ in_array($question->id, old('questions', $selectedQuestionIds)) ? 'checked' : '' }}
                                            >
                                            <label for="question_{{ $question->id }}" class="ml-2 text-gray-700 dark:text-gray-300">{{ $question->text }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <p x-show="errors.questions" class="text-red-500 text-sm mt-1" x-text="errors.questions"></p>
                            </div>

                            <div>
                                <label for="new_question" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Add New Question (Optional)
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Enter a new question if needed</span>
                                </label>
                                <input
                                    type="text"
                                    id="new_question"
                                    name="new_question"
                                    x-model="form.new_question"
                                    placeholder="e.g., How effective was the task execution?"
                                    class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                            <div class="mt-6 p-4 bg-gray-100 rounded-lg space-y-2 dark:bg-gray-700">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Review Your Changes</h3>
                                <p><strong class="text-gray-700 dark:text-gray-300">Project:</strong> <span x-text="getProjectName(form.project_id)" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Task Name:</strong> <span x-text="form.name || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Description:</strong> <span x-text="form.description || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Start Date:</strong> <span x-text="form.start_date || 'Not set'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">End Date:</strong> <span x-text="form.end_date || 'Not set'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Status:</strong> <span x-text="form.status || 'Not selected'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Assigned To:</strong> <span x-text="getEmployeeName(form.assigned_to)" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Questions:</strong> <span x-text="getSelectedQuestionsText()" class="text-gray-900 dark:text-white"></span></p>
                                <p x-show="form.new_question"><strong class="text-gray-700 dark:text-gray-300">New Question:</strong> <span x-text="form.new_question" class="text-gray-900 dark:text-white"></span></p>
                            </div>

                            <div class="button-container">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                                    @mousedown="addRipple($event)"
                                >
                                    Previous
                                </button>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600 disabled:opacity-50"
                                    @mousedown="addRipple($event)"
                                >
                                    <span x-show="!isSubmitting">Confirm and Update</span>
                                    <span x-show="isSubmitting" class="flex items-center"><i class="fas fa-spinner fa-spin mr-2"></i>Updating...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showConfetti" class="fixed inset-0 pointer-events-none z-50">
            <template x-for="i in 50">
                <div class="absolute w-2 h-2 rounded" :style="{ left: Math.random() * 100 + 'vw', top: '-10px', backgroundColor: ['#8B2BE2', '#FF6F61', '#FFD700', '#40C4FF'][Math.floor(Math.random() * 4)], animation: 'confetti ' + (1 + Math.random()) + 's ease forwards', animationDelay: Math.random() * 0.5 + 's' }"></div>
            </template>
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

        function taskForm() {
            return {
                step: 1,
                showConfetti: false,
                isSubmitting: false,
                form: {
                    project_id: '{{ old('project_id', $task->project_id) }}',
                    name: '{{ old('name', $task->name) }}',
                    description: '{{ old('description', $task->description) }}',
                    start_date: '{{ old('start_date', $task->start_date) }}',
                    end_date: '{{ old('end_date', $task->end_date) }}',
                    status: '{{ old('status', $task->status) }}',
                    assigned_to: '{{ old('assigned_to', $task->assigned_to) }}',
                    questions: @json(old('questions', $selectedQuestionIds)),
                    new_question: '{{ old('new_question') }}'
                },
                errors: {
                    project_id: '',
                    name: '',
                    assigned_to: '',
                    dates: '',
                    questions: ''
                },
                projects: @json($projects),
                employees: @json($employees),
                questions: @json($questions),
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Task name is required' : '';
                },
                validateProject() {
                    this.errors.project_id = this.form.project_id === '' ? 'Project is required' : '';
                },
                validateAssignedTo() {
                    this.errors.assigned_to = this.form.assigned_to === '' ? 'Assignee is required' : '';
                },
                validateDates() {
                    if (this.form.start_date && this.form.end_date) {
                        const start = new Date(this.form.start_date);
                        const end = new Date(this.form.end_date);
                        this.errors.dates = start > end ? 'End date must be after start date' : '';
                    } else {
                        this.errors.dates = '';
                    }
                },
                validateQuestions() {
                    const selectedCount = this.form.questions ? this.form.questions.length : 0;
                    this.errors.questions = selectedCount > 3 ? 'You can select a maximum of 3 questions' : '';
                },
                nextStep() {
                    if (this.step === 1) {
                        this.validateName();
                        this.validateProject();
                        if (this.errors.name || this.errors.project_id) return;
                    } else if (this.step === 2) {
                        this.validateAssignedTo();
                        this.validateDates();
                        if (this.errors.assigned_to || this.errors.dates) return;
                    } else if (this.step === 3) {
                        this.validateQuestions();
                        if (this.errors.questions) return;
                    }
                    this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                },
                getProjectName(id) {
                    const project = this.projects.find(p => p.id == id);
                    return project ? project.name : 'Not selected';
                },
                getEmployeeName(id) {
                    const employee = this.employees.find(e => e.id == id);
                    return employee ? employee.username : 'Not assigned';
                },
                getSelectedQuestionsText() {
                    if (!this.form.questions || this.form.questions.length === 0) {
                        return 'No questions selected';
                    }
                    const selectedQuestions = this.questions.filter(q =>
                        this.form.questions.includes(q.id.toString())
                    );
                    return selectedQuestions.map(q => q.text).join(', ');
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
                submitForm(event) {
                    event.preventDefault();
                    this.isSubmitting = true;
                    this.showConfetti = true;
                    setTimeout(() => this.$refs.form.submit(), 2000);
                }
            };
        }
    </script>
@endsection
