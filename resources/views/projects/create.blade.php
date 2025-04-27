@extends('layouts.app')

@section('title', 'Create New Project')

@section('content')
    <div x-data="projectForm()" class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Create New Project</h2>
        <p class="text-gray-600 mb-6">Fill in the details to start a new project</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-green-500 font-bold': step >= 1 }">1. Project Details</div>
                <div class="flex-1 text-center" :class="{ 'text-green-500 font-bold': step >= 2 }">2. Timeline</div>
                <div class="flex-1 text-center" :class="{ 'text-green-500 font-bold': step >= 3 }">3. Team</div>
                <div class="flex-1 text-center" :class="{ 'text-green-500 font-bold': step >= 4 }">4. Review</div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" :style="{ width: (step / 4) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('projects.store') }}" method="POST" x-ref="form">
            @csrf

            <!-- Step 1: Project Details -->
            <div x-show="step === 1">
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                        Project Name <span class="text-red-500">*</span>
                        <span class="inline-block ml-1 text-gray-500 cursor-pointer" title="A unique name to identify your project">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        x-model="form.name"
                        @input="validateName"
                        placeholder="e.g., Mobile App Redesign"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        required
                        aria-required="true"
                    >
                    <p x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></p>
                </div>
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                        Description
                        <span class="inline-block ml-1 text-gray-500 cursor-pointer" title="Briefly outline the project’s goals and deliverables">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        x-model="form.description"
                        placeholder="e.g., Develop a new feature to improve user login experience"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 h-32 resize-none"
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <button
                        type="button"
                        @click="nextStep"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2: Timeline -->
            <div x-show="step === 2">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">
                            Start Date
                            <span class="inline-block ml-1 text-gray-500 cursor-pointer" title="The date when the project begins">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            x-model="form.start_date"
                            @input="validateDates"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">
                            End Date <span class="text-red-500">*</span>
                            <span class="inline-block ml-1 text-gray-500 cursor-pointer" title="Must be later than the start date">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        </label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            x-model="form.end_date"
                            @input="validateDates"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
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
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition"
                    >
                        Previous
                    </button>
                    <button
                        type="button"
                        @click="nextStep"
                        :disabled="errors.dates || !form.end_date"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3: Team -->
            <div x-show="step === 3">
                @if(auth()->user()->role === 'Admin')
                    <div class="mb-6">
                        <label for="manager_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            Project Manager
                            <span class="inline-block ml-1 text-gray-500 cursor-pointer" title="Select a manager (optional)">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        </label>
                        <select
                            id="manager_id"
                            name="manager_id"
                            x-model="form.manager_id"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            <option value="">Select a manager (optional)</option>
                            @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->username }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="mb-6">
                    <label for="team_members" class="block text-sm font-semibold text-gray-700 mb-1">
                        Team Members
                        <span class="inline-block ml-1 text-gray-500 cursor-pointer" title="Select team members for the project">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="team_members"
                        name="employees[]"
                        x-model="form.team_members"
                        multiple
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 h-32"
                    >
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple members</p>
                </div>
                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="prevStep"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition"
                    >
                        Previous
                    </button>
                    <button
                        type="button"
                        @click="nextStep"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 4: Review -->
            <div x-show="step === 4">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Review Your Project</h3>
                <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                    <p><strong>Project Name:</strong> <span x-text="form.name || 'Not provided'"></span></p>
                    <p><strong>Description:</strong> <span x-text="form.description || 'Not provided'"></span></p>
                    <p><strong>Start Date:</strong> <span x-text="form.start_date || 'Not provided'"></span></p>
                    <p><strong>End Date:</strong> <span x-text="form.end_date || 'Not provided'"></span></p>
                    <p><strong>Project Manager:</strong>
                        <span x-text="form.manager_id ? $refs.form.querySelector(`#manager_id option[value='${form.manager_id}']`)?.text || 'Not assigned' : 'Not assigned'"></span>
                    </p>
                    <p><strong>Team Members:</strong>
                        <span x-text="form.team_members.length ? Array.from(form.team_members).map(id => $refs.form.querySelector(`#team_members option[value='${id}']`)?.text || '').join(', ') : 'None selected'"></span>
                    </p>
                </div>
                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="prevStep"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition"
                    >
                        Previous
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
                    >
                        Confirm and Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function projectForm() {
            return {
                step: 1,
                form: {
                    name: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    manager_id: '',
                    team_members: []
                },
                errors: {
                    name: '',
                    dates: ''
                },
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Project name is required' : '';
                },
                validateDates() {
                    // Clear previous errors
                    this.errors.dates = '';

                    // Check if end_date is provided (since it's required)
                    if (!this.form.end_date) {
                        this.errors.dates = 'End date is required';
                        return;
                    }

                    // If both dates are provided, validate that end_date is after start_date
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
                }
            };
        }
    </script>
@endsection
