@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
    <div x-data="taskForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Create New Task</h2>
        <p class="text-gray-600 mb-8">Fill in the details to create a new task</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. Task Details</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Timeline</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 3 }">3. Assignment</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 4 }">4. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 4) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('tasks.store') }}" method="POST" x-ref="form">
            @csrf

            <!-- Step 1: Task Details -->
            <div x-show="step === 1">
                <!-- Project Selection -->
                <div class="mb-6">
                    <label for="project_id" class="block text-sm font-semibold text-gray-600 mb-2">
                        Project <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Select the project this task belongs to">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="project_id"
                        name="project_id"
                        x-model="form.project_id"
                        @change="validateProject"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                        <option value="" class="text-gray-400">-- Select Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }} class="text-gray-800">
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="errors.project_id" class="text-red-600 text-sm mt-2" x-text="errors.project_id"></p>
                    @error('project_id')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Task Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-600 mb-2">
                        Task Name <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="A unique name to identify your task">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        x-model="form.name"
                        @input="validateName"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                    <p x-show="errors.name" class="text-red-600 text-sm mt-2" x-text="errors.name"></p>
                    @error('name')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Task Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-600 mb-2">
                        Description
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Briefly outline the task’s goals and deliverables">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        x-model="form.description"
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition h-32 resize-y"
                    >{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button
                        type="button"
                        @click="nextStep"
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2: Timeline -->
            <div x-show="step === 2">
                <!-- Start Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-gray-600 mb-2">
                            Start Date
                            <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="The date when the task begins">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            x-model="form.start_date"
                            @input="validateDates"
                            class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        >
                        @error('start_date')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-gray-600 mb-2">
                            End Date
                            <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="The date when the task is expected to be completed">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        </label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            x-model="form.end_date"
                            @input="validateDates"
                            class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        >
                        <p x-show="errors.dates" class="text-red-600 text-sm mt-2" x-text="errors.dates"></p>
                        @error('end_date')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="prevStep"
                        class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold"
                    >
                        Previous
                    </button>
                    <button
                        type="button"
                        @click="nextStep"
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3: Assignment -->
            <div x-show="step === 3">
                <!-- Status Selection -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-600 mb-2">
                        Status <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="The current status of the task">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="status"
                        name="status"
                        x-model="form.status"
                        @change="validateStatus"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                        <option value="Not Started" {{ old('status', 'Not Started') == 'Not Started' ? 'selected' : '' }} class="text-gray-800">Not Started</option>
                        <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }} class="text-gray-800">In Progress</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }} class="text-gray-800">Completed</option>
                    </select>
                    <p x-show="errors.status" class="text-red-600 text-sm mt-2" x-text="errors.status"></p>
                    @error('status')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assigned Employee -->
                <div class="mb-6">
                    <label for="assigned_to" class="block text-sm font-semibold text-gray-600 mb-2">
                        Assign to Employee <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Select an employee to assign the task to">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="assigned_to"
                        name="assigned_to"
                        x-model="form.assigned_to"
                        @change="validateAssignedTo"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                        <option value="" class="text-gray-400">-- Select Employee --</option>
                        @forelse($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }} class="text-gray-800">
                                {{ $employee->username }}
                            </option>
                        @empty
                            <option value="" disabled class="text-gray-400">No employees available in your group</option>
                        @endforelse
                    </select>
                    <p x-show="errors.assigned_to" class="text-red-600 text-sm mt-2" x-text="errors.assigned_to"></p>
                    @error('assigned_to')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    @if($employees->isEmpty() && auth()->user()->role === 'Manager')
                        <p class="text-yellow-600 text-sm mt-2">Warning: No employees found in your group to assign tasks to.</p>
                    @endif
                </div>

                <!-- Select Existing Questions -->
                <div class="mb-6">
                    <label for="questions" class="block text-sm font-semibold text-gray-600 mb-2">
                        Select Questions <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Select questions relevant to this task">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="questions"
                        name="questions[]"
                        x-model="form.questions"
                        @change="validateQuestions"
                        multiple
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition h-32"
                        aria-required="true"
                    >
                        @foreach($questions as $question)
                            <option value="{{ $question->id }}" {{ in_array($question->id, old('questions', [])) ? 'selected' : '' }} class="text-gray-800">
                                {{ $question->text }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-400 text-sm mt-2">Hold Ctrl/Cmd to select multiple questions</p>
                    <p x-show="errors.questions" class="text-red-600 text-sm mt-2" x-text="errors.questions"></p>
                    @error('questions')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Add New Question (optional) -->
                <div class="mb-6">
                    <label for="new_question" class="block text-sm font-semibold text-gray-600 mb-2">
                        Add New Question (optional)
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Add a new question if needed">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="text"
                        id="new_question"
                        name="new_question"
                        x-model="form.new_question"
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        placeholder="Enter a new question..."
                    >
                    @error('new_question')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="prevStep"
                        class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold"
                    >
                        Previous
                    </button>
                    <button
                        type="button"
                        @click="nextStep"
                        :disabled="errors.status || errors.assigned_to || errors.questions || !form.status || !form.assigned_to || !form.questions.length"
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 4: Review -->
            <div x-show="step === 4">
                <h3 class="text-lg font-semibold mb-4 text-black">Review Your Task</h3>
                <div class="mb-6 p-4 bg-[#F5F5F5] rounded-lg">
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
                        class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold"
                    >
                        Previous
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold"
                    >
                        Confirm and Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function taskForm() {
            return {
                step: 1,
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
                }
            };
        }
    </script>
@endsection
