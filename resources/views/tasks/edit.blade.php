@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <div x-data="taskForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Edit Task</h2>
        <p class="text-gray-600 mb-8">Update the task details</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. Basic Info</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Scheduling</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 3 }">3. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 3) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" x-ref="form">
            @csrf
            @method('PUT')

            <!-- Step 1 -->
            <div x-show="step === 1" class="space-y-6">
                <!-- Project -->
                <div>
                    <label for="project_id" class="block text-sm font-semibold text-gray-600 mb-2">Project <span class="text-red-600">*</span></label>
                    <select name="project_id" id="project_id" x-model="form.project_id"
                            class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="errors.project_id" class="text-red-600 text-sm mt-2" x-text="errors.project_id"></p>
                    @error('project_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Task Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-600 mb-2">Task Name <span class="text-red-600">*</span></label>
                    <input type="text" id="name" name="name" x-model="form.name"
                           @input="validateName"
                           class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                    <p x-show="errors.name" class="text-red-600 text-sm mt-2" x-text="errors.name"></p>
                    @error('name') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                    <textarea id="description" name="description" x-model="form.description" rows="3"
                              class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">{{ old('description', $task->description) }}</textarea>
                    @error('description') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="nextStep"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2 -->
            <div x-show="step === 2" class="space-y-6">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                    <input type="date" id="start_date" name="start_date" x-model="form.start_date"
                           class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                    @error('start_date') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-600 mb-2">End Date</label>
                    <input type="date" id="end_date" name="end_date" x-model="form.end_date"
                           @change="validateDates"
                           class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                    <p x-show="errors.dates" class="text-red-600 text-sm mt-2" x-text="errors.dates"></p>
                    @error('end_date') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                    <select id="status" name="status" x-model="form.status"
                            class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                        @foreach(['Not Started', 'In Progress', 'Completed'] as $status)
                            <option value="{{ $status }}" {{ old('status', $task->status) === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-semibold text-gray-600 mb-2">Assign To <span class="text-red-600">*</span></label>
                    <select id="assigned_to" name="assigned_to" x-model="form.assigned_to"
                            class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('assigned_to', $task->assigned_to) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->username }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="errors.assigned_to" class="text-red-600 text-sm mt-2" x-text="errors.assigned_to"></p>
                    @error('assigned_to') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="prevStep"
                            class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold">
                        Previous
                    </button>
                    <button type="button" @click="nextStep"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3 -->
            <div x-show="step === 3" class="space-y-6">
                <!-- Questions -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Questions (max 3)</label>
                    <div class="space-y-2">
                        @foreach($questions as $question)
                            <div class="flex items-center">
                                <input type="checkbox" id="question_{{ $question->id }}" name="questions[]"
                                       value="{{ $question->id }}" x-model="form.questions"
                                       class="h-4 w-4 text-[#28A745] focus:ring-[#28A745] border-gray-300 rounded"
                                    {{ in_array($question->id, old('questions', $selectedQuestionIds)) ? 'checked' : '' }}>
                                <label for="question_{{ $question->id }}" class="ml-2 text-gray-700">{{ $question->text }}</label>
                            </div>
                        @endforeach
                    </div>
                    <p x-show="errors.questions" class="text-red-600 text-sm mt-2" x-text="errors.questions"></p>
                    @error('questions') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Optional New Question -->
                <div>
                    <label for="new_question" class="block text-sm font-semibold text-gray-600 mb-2">Add New Question (Optional)</label>
                    <input type="text" id="new_question" name="new_question" x-model="form.new_question"
                           class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                    @error('new_question') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Review Section -->
                <div class="mt-6 p-4 bg-[#F5F5F5] rounded-lg space-y-2">
                    <h3 class="text-lg font-semibold mb-2 text-black">Review Your Changes</h3>
                    <p><strong>Project:</strong> <span x-text="getProjectName(form.project_id)"></span></p>
                    <p><strong>Task Name:</strong> <span x-text="form.name || 'Not provided'"></span></p>
                    <p><strong>Description:</strong> <span x-text="form.description || 'Not provided'"></span></p>
                    <p><strong>Start Date:</strong> <span x-text="form.start_date || 'Not set'"></span></p>
                    <p><strong>End Date:</strong> <span x-text="form.end_date || 'Not set'"></span></p>
                    <p><strong>Status:</strong> <span x-text="form.status || 'Not selected'"></span></p>
                    <p><strong>Assigned To:</strong> <span x-text="getEmployeeName(form.assigned_to)"></span></p>
                    <p><strong>Questions:</strong> <span x-text="getSelectedQuestionsText()"></span></p>
                    <p x-show="form.new_question"><strong>New Question:</strong> <span x-text="form.new_question"></span></p>
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="prevStep"
                            class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold">
                        Previous
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">
                        Confirm and Update
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
                    // Validate current step before proceeding
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
                    this.step--;
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
                }
            };
        }
    </script>
@endsection
