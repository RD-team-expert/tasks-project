@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div x-data="userForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Create New User</h2>
        <p class="text-gray-600 mb-8">Fill in the details to create a new user</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. User Details</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Role</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 3 }">3. Assignments</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 4 }">4. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 4) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('users.store') }}" method="POST" x-ref="form">
            @csrf

            <!-- Step 1: User Details -->
            <div x-show="step === 1">
                <!-- Username -->
                <div class="mb-6">
                    <label for="username" class="block text-sm font-semibold text-gray-600 mb-2">
                        Username <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Enter a unique username">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        x-model="form.username"
                        @input="validateUsername"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                    <p x-show="errors.username" class="text-red-600 text-sm mt-2" x-text="errors.username"></p>
                    @error('username')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-600 mb-2">
                        Email <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Enter the user's email address">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        x-model="form.email"
                        @input="validateEmail"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                    <p x-show="errors.email" class="text-red-600 text-sm mt-2" x-text="errors.email"></p>
                    @error('email')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-600 mb-2">
                        Password <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Enter a secure password (minimum 8 characters)">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        x-model="form.password"
                        @input="validatePassword"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                    <p x-show="errors.password" class="text-red-600 text-sm mt-2" x-text="errors.password"></p>
                    @error('password')
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

            <!-- Step 2: Role -->
            <div x-show="step === 2">
                <!-- Role Selection -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-semibold text-gray-600 mb-2">
                        Role <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Select the user's role">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        x-model="form.role"
                        @change="validateRole"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                        <option value="" class="text-gray-400">Select Role</option>
                        <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }} class="text-gray-800">Admin</option>
                        <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }} class="text-gray-800">Manager</option>
                        <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }} class="text-gray-800">Employee</option>
                    </select>
                    <p x-show="errors.role" class="text-red-600 text-sm mt-2" x-text="errors.role"></p>
                    @error('role')
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
                        :disabled="errors.role || !form.role"
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3: Assignments -->
            <div x-show="step === 3">
                <!-- Manager Selection -->
                <div class="mb-6">
                    <label for="manager_id" class="block text-sm font-semibold text-gray-600 mb-2">
                        Assign Manager (Optional)
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Assign a manager to the user">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="manager_id"
                        name="manager_id"
                        x-model="form.manager_id"
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                    >
                        <option value="" class="text-gray-400">Assign Manager (Optional)</option>
                        @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }} class="text-gray-800">
                                {{ $manager->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Selection -->
                <div class="mb-6">
                    <label for="group_id" class="block text-sm font-semibold text-gray-600 mb-2">
                        Assign Group (Optional)
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Assign the user to a group">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="group_id"
                        name="group_id"
                        x-model="form.group_id"
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                    >
                        <option value="" class="text-gray-400">Assign Group (Optional)</option>
                        @foreach(\App\Models\Group::all() as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }} class="text-gray-800">
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_id')
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
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 4: Review -->
            <div x-show="step === 4">
                <h3 class="text-lg font-semibold mb-4 text-black">Review Your User</h3>
                <div class="mb-6 p-4 bg-[#F5F5F5] rounded-lg">
                    <p><strong>Username:</strong> <span x-text="form.username || 'Not provided'"></span></p>
                    <p><strong>Email:</strong> <span x-text="form.email || 'Not provided'"></span></p>
                    <p><strong>Password:</strong> <span x-text="form.password ? '********' : 'Not provided'"></span></p>
                    <p><strong>Role:</strong> <span x-text="form.role || 'Not selected'"></span></p>
                    <p><strong>Manager:</strong> <span x-text="form.manager_id ? $refs.form.querySelector(`#manager_id option[value='${form.manager_id}']`)?.text || 'Not assigned' : 'Not assigned'"></span></p>
                    <p><strong>Group:</strong> <span x-text="form.group_id ? $refs.form.querySelector(`#group_id option[value='${form.group_id}']`)?.text || 'Not assigned' : 'Not assigned'"></span></p>
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
        function userForm() {
            return {
                step: 1,
                form: {
                    username: '{{ old('username') }}',
                    email: '{{ old('email') }}',
                    password: '',
                    role: '{{ old('role') }}',
                    manager_id: '{{ old('manager_id') }}',
                    group_id: '{{ old('group_id') }}',
                },
                errors: {
                    username: '',
                    email: '',
                    password: '',
                    role: ''
                },
                validateUsername() {
                    this.errors.username = this.form.username.trim() === '' ? 'Username is required' : '';
                },
                validateEmail() {
                    this.errors.email = this.form.email.trim() === '' ? 'Email is required' : '';
                    if (this.form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                        this.errors.email = 'Please enter a valid email address';
                    }
                },
                validatePassword() {
                    this.errors.password = this.form.password.length < 8 ? 'Password must be at least 8 characters long' : '';
                },
                validateRole() {
                    this.errors.role = this.form.role ? '' : 'Please select a role';
                },
                nextStep() {
                    if (this.step === 1) {
                        this.validateUsername();
                        this.validateEmail();
                        this.validatePassword();
                        if (this.errors.username || this.errors.email || this.errors.password) return;
                    }
                    if (this.step === 2) {
                        this.validateRole();
                        if (this.errors.role) return;
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
