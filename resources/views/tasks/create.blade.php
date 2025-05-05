@extends('layouts.app')

@section('title', 'Create New Task')

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
        .drag-over { border: 2px dashed #8B2BE2; background: rgba(138, 43, 226, 0.1); }
        .tooltip { visibility: hidden; opacity: 0; transition: all 0.3s ease; transform: translateY(10px); }
        .tooltip-parent:hover .tooltip { visibility: visible; opacity: 1; transform: translateY(0); }
        .error-message { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .button-container { display: flex; justify-content: center; gap: 1rem; }

        /* Dark Mode Styles */
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
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">Create New Task</h2>
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
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 1 }">Details</div>
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 2 }">Timeline</div>
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 3 }">Assignment</div>
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 4 }">Questions</div>
                            <div :class="{ 'text-[#8B2BE2] font-bold': step >= 5 }">Review</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-600">
                            <div class="bg-[#8B2BE2] h-2 rounded-full transition-all duration-500" :style="{ width: (step / 5) * 100 + '%' }"></div>
                        </div>
                    </div>

                    <form action="{{ route('tasks.store') }}" method="POST" x-ref="form" @submit="submitForm">
                        @csrf

                        <div x-show="step === 1" class="animate-slide-in">
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Task Name <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">A unique name</span>
                                </label>
                                <input type="text" id="name" name="name" x-model="form.name" @input="validateName" placeholder="e.g., Implement Login" class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white" :class="{ 'border-red-500 animate-shake': errors.name }" required>
                                <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                            </div>
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Description
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Task objectives</span>
                                </label>
                                <textarea id="description" name="description" x-model="form.description" placeholder="e.g., Develop a login system" class="w-full px-4 py-2 border rounded-lg input-focus h-32 resize-none dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"></textarea>
                            </div>
                            <div class="button-container">
                                <button type="button" @click="nextStep" class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600" @mousedown="addRipple($event)">Next</button>
                            </div>
                        </div>

                        <div x-show="step === 2" class="animate-slide-in">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                        Start Date
                                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Task start</span>
                                    </label>
                                    <input type="date" id="start_date" name="start_date" x-model="form.start_date" @input="validateDates" class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                        End Date <span class="text-red-500">*</span>
                                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">After start date</span>
                                    </label>
                                    <input type="date" id="end_date" name="end_date" x-model="form.end_date" @input="validateDates" class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white" :class="{ 'border-red-500 animate-shake': errors.dates }" required>
                                    <p x-show="errors.dates" class="text-red-500 text-sm mt-1" x-text="errors.dates"></p>
                                </div>
                            </div>
                            <div class="button-container">
                                <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Previous</button>
                                <button type="button" @click="nextStep" :disabled="errors.dates || !form.end_date" class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600 disabled:opacity-50" @mousedown="addRipple($event)">Next</button>
                            </div>
                        </div>

                        <div x-show="step === 3" class="animate-slide-in">
                            <div class="mb-6">
                                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Project <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select project</span>
                                </label>
                                <select id="project_id" name="project_id" x-model="form.project_id" @change="validateProject" class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white" :class="{ 'border-red-500 animate-shake': errors.project }" required>
                                    <option value="">Select a project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <p x-show="errors.project" class="text-red-500 text-sm mt-1" x-text="errors.project"></p>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Assign To <span class="text-red-500">*</span>
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Drag to assign one employee</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="p-4 border rounded-lg dark:border-gray-600" @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop="dropUser($event, 'available')" :class="{ 'drag-over': isDragging && dragTarget === 'available' }">
                                        <h4 class="text-sm font-semibold text-gray-600 mb-2 dark:text-gray-300">Available</h4>
                                        <template x-for="user in availableUsers" :key="user.id">
                                            <div class="p-2 bg-gray-100 rounded mb-2 cursor-move hover:bg-gray-200 transition dark:bg-gray-600 dark:hover:bg-gray-500" draggable="true" @dragstart="dragUser($event, user, 'available')" x-text="user.username"></div>
                                        </template>
                                        <p x-show="availableUsers.length === 0" class="text-gray-500 text-sm dark:text-gray-400">No available users</p>
                                    </div>
                                    <div class="p-4 border rounded-lg dark:border-gray-600" @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop="dropUser($event, 'team')" :class="{ 'drag-over': isDragging && dragTarget === 'team' }">
                                        <h4 class="text-sm font-semibold text-gray-600 mb-2 dark:text-gray-300">Assigned</h4>
                                        <template x-for="member in form.team_members" :key="member.id">
                                            <div class="p-2 bg-[#8B2BE2] text-white rounded mb-2 cursor-move hover:bg-[#7A26C9] transition" draggable="true" @dragstart="dragUser($event, member, 'team')" x-text="member.username"></div>
                                        </template>
                                        <p x-show="form.team_members.length === 0" class="text-gray-500 text-sm dark:text-gray-400">No assigned employee</p>
                                    </div>
                                    <div class="p-4 border rounded-lg dark:border-gray-600" @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop="dropUser($event, 'pending')" :class="{ 'drag-over': isDragging && dragTarget === 'pending' }">
                                        <h4 class="text-sm font-semibold text-gray-600 mb-2 dark:text-gray-300">Pending Review</h4>
                                        <template x-for="member in pendingReview" :key="member.id">
                                            <div class="p-2 bg-yellow-300 rounded mb-2 cursor-move hover:bg-yellow-400 transition" draggable="true" @dragstart="dragUser($event, member, 'pending')" x-text="member.username"></div>
                                        </template>
                                        <p x-show="pendingReview.length === 0" class="text-gray-500 text-sm dark:text-gray-400">No pending reviews</p>
                                    </div>
                                </div>
                                <input type="hidden" name="assigned_to" x-model="form.team_members[0]?.id">
                                <p x-show="errors.assigned_to" class="text-red-500 text-sm mt-1" x-text="errors.assigned_to"></p>
                            </div>
                            <div class="button-container">
                                <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Previous</button>
                                <button type="button" @click="nextStep" :disabled="errors.project || !form.project_id || form.team_members.length === 0" class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600 disabled:opacity-50" @mousedown="addRipple($event)">Next</button>
                            </div>
                        </div>

                        <div x-show="step === 4" class="animate-slide-in">
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Evaluation Questions (Max 3)
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Select up to 3</span>
                                </label>
                                <div class="space-y-2">
                                    <template x-for="question in questions" :key="question.id">
                                        <div class="flex items-center">
                                            <input type="checkbox" :id="'question-' + question.id" name="questions[]" :value="question.id" x-model="form.questions" @change="validateQuestions" class="mr-2">
                                            <label :for="'question-' + question.id" class="text-sm text-gray-700 dark:text-gray-300" x-text="question.text"></label>
                                        </div>
                                    </template>
                                </div>
                                <p x-show="errors.questions" class="text-red-500 text-sm mt-1" x-text="errors.questions"></p>
                            </div>
                            <div class="mb-6">
                                <label for="new_question" class="block text-sm font-semibold text-gray-700 mb-1 relative tooltip-parent dark:text-gray-200">
                                    Add New Question
                                    <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Optional custom question</span>
                                </label>
                                <input type="text" id="new_question" name="new_question" x-model="form.new_question" placeholder="e.g., How effective?" class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            </div>
                            <div class="button-container">
                                <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Previous</button>
                                <button type="button" @click="nextStep" class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600" @mousedown="addRipple($event)">Next</button>
                            </div>
                        </div>

                        <div x-show="step === 5" class="animate-slide-in">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Review Your Task</h3>
                            <div class="mb-4 p-4 bg-gray-100 rounded-lg dark:bg-gray-700">
                                <p><strong class="text-gray-700 dark:text-gray-300">Task Name:</strong> <span x-text="form.name || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Description:</strong> <span x-text="form.description || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Start Date:</strong> <span x-text="form.start_date || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">End Date:</strong> <span x-text="form.end_date || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Project:</strong> <span x-text="form.project_id ? projects.find(p => p.id == form.project_id)?.name || 'Not selected' : 'Not selected'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Assigned To:</strong> <span x-text="form.team_members.length ? form.team_members.map(m => m.username).join(', ') : 'None'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">Questions:</strong> <span x-text="form.questions.length ? form.questions.map(qid => questions.find(q => q.id == qid)?.text).join(', ') : 'None'" class="text-gray-900 dark:text-white"></span></p>
                                <p><strong class="text-gray-700 dark:text-gray-300">New Question:</strong> <span x-text="form.new_question || 'Not provided'" class="text-gray-900 dark:text-white"></span></p>
                            </div>
                            <div class="button-container">
                                <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Previous</button>
                                <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600 disabled:opacity-50" @mousedown="addRipple($event)">
                                    <span x-show="!isSubmitting">Confirm and Submit</span>
                                    <span x-show="isSubmitting" class="flex items-center"><i class="fas fa-spinner fa-spin mr-2"></i>Submitting...</span>
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
                    name: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    project_id: '',
                    team_members: [],
                    questions: [],
                    new_question: ''
                },
                projects: @json($projects),
                employees: @json($employees),
                questions: @json($questions),
                availableUsers: @json($employees),
                pendingReview: [],
                errors: { name: '', dates: '', project: '', questions: '', assigned_to: '' },
                draggedUser: null,
                dragTarget: '',
                isDragging: false,
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Required' : '';
                },
                validateDates() {
                    this.errors.dates = !this.form.end_date ? 'Required' :
                        (this.form.start_date && new Date(this.form.end_date) < new Date(this.form.start_date) ? 'After start date' : '');
                },
                validateProject() {
                    this.errors.project = !this.form.project_id ? 'Required' : '';
                },
                validateQuestions() {
                    this.errors.questions = this.form.questions.length > 3 ? 'Max 3' : '';
                },
                validateAssignedTo() {
                    this.errors.assigned_to = this.form.team_members.length === 0 ? 'Please assign one employee' : '';
                },
                nextStep() {
                    this.validateName();
                    this.validateDates();
                    this.validateProject();
                    this.validateQuestions();
                    this.validateAssignedTo();
                    if (this.step === 1 && this.errors.name) return;
                    if (this.step === 2 && (this.errors.dates || !this.form.end_date)) return;
                    if (this.step === 3 && (this.errors.project || this.errors.assigned_to)) return;
                    if (this.step === 4 && this.errors.questions) return;
                    if (this.step < 5) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                },
                dragUser(event, user, target) {
                    this.draggedUser = user;
                    this.dragTarget = target;
                    event.dataTransfer.setData('text/plain', JSON.stringify(user));
                },
                dropUser(event, target) {
                    event.preventDefault();
                    this.isDragging = false;
                    const user = this.draggedUser;
                    if (!user) return;
                    const fromAvailable = this.availableUsers.find(u => u.id === user.id);
                    const fromTeam = this.form.team_members.find(m => m.id === user.id);
                    const fromPending = this.pendingReview.find(p => p.id === user.id);
                    if (target === 'available') {
                        if (fromTeam) {
                            this.form.team_members = [];
                            this.availableUsers.push(user);
                        } else if (fromPending) {
                            this.pendingReview = this.pendingReview.filter(p => p.id !== user.id);
                            this.availableUsers.push(user);
                        }
                    } else if (target === 'team') {
                        if (fromAvailable || fromPending) {
                            this.form.team_members = [user]; // Only allow one team member
                            this.availableUsers = this.availableUsers.filter(u => u.id !== user.id);
                            this.pendingReview = this.pendingReview.filter(p => p.id !== user.id);
                        }
                    } else if (target === 'pending') {
                        if (fromAvailable) {
                            this.availableUsers = this.availableUsers.filter(u => u.id !== user.id);
                            this.pendingReview.push(user);
                        } else if (fromTeam) {
                            this.form.team_members = [];
                            this.pendingReview.push(user);
                        }
                    }
                    this.validateAssignedTo();
                    this.draggedUser = null;
                    this.dragTarget = '';
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
