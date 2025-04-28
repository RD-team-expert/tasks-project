@extends('layouts.app')

@section('title', 'Create Group')

@section('content')
    <div x-data="groupForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Create New Group</h2>
        <p class="text-gray-600 mb-8">Fill in the details to create a new group</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. Group Details</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Team</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 3 }">3. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 3) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('groups.store') }}" method="POST" x-ref="form">
            @csrf

            <!-- Step 1: Group Details -->
            <div x-show="step === 1">
                <!-- Group Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-600 mb-2">
                        Group Name <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="A unique name to identify your group">
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

            <!-- Step 2: Team -->
            <div x-show="step === 2">
                <!-- Manager Selection -->
                <div class="mb-6">
                    <label for="manager_id" class="block text-sm font-semibold text-gray-600 mb-2">
                        Select Manager <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Select a manager for the group">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="manager_id"
                        name="manager_id"
                        x-model="form.manager_id"
                        @change="validateManager"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                        <option value="" class="text-gray-400">-- Select Manager --</option>
                        @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }} class="text-gray-800">
                                {{ $manager->username ?? $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="errors.manager_id" class="text-red-600 text-sm mt-2" x-text="errors.manager_id"></p>
                    @error('manager_id')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Employees Selection -->
                <div class="mb-6">
                    <label for="employees" class="block text-sm font-semibold text-gray-600 mb-2">
                        Select Employees (Optional)
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Select employees to add to the group">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <select
                        id="employees"
                        name="employees[]"
                        x-model="form.employees"
                        multiple
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition h-32"
                    >
                        @foreach(\App\Models\User::where('role', 'Employee')->get() as $employee)
                            <option value="{{ $employee->id }}" {{ in_array($employee->id, old('employees', [])) ? 'selected' : '' }} class="text-gray-800">
                                {{ $employee->username ?? $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-400 text-sm mt-2">Hold Ctrl/Cmd to select multiple employees</p>
                    @error('employees')
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
                        :disabled="errors.manager_id || !form.manager_id"
                        class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div x-show="step === 3">
                <h3 class="text-lg font-semibold mb-4 text-black">Review Your Group</h3>
                <div class="mb-6 p-4 bg-[#F5F5F5] rounded-lg">
                    <p><strong>Group Name:</strong> <span x-text="form.name || 'Not provided'"></span></p>
                    <p><strong>Manager:</strong> <span x-text="form.manager_id ? $refs.form.querySelector(`#manager_id option[value='${form.manager_id}']`)?.text || 'Not selected' : 'Not selected'"></span></p>
                    <p><strong>Employees:</strong> <span x-text="form.employees.length ? Array.from(form.employees).map(id => $refs.form.querySelector(`#employees option[value='${id}']`)?.text || '').join(', ') : 'None selected'"></span></p>
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
        function groupForm() {
            return {
                step: 1,
                form: {
                    name: '{{ old('name') }}',
                    manager_id: '{{ old('manager_id') }}',
                    employees: @json(old('employees', [])),
                },
                errors: {
                    name: '',
                    manager_id: ''
                },
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Group name is required' : '';
                },
                validateManager() {
                    this.errors.manager_id = this.form.manager_id ? '' : 'Please select a manager';
                },
                nextStep() {
                    if (this.step === 1) {
                        this.validateName();
                        if (this.errors.name) return;
                    }
                    if (this.step === 2) {
                        this.validateManager();
                        if (this.errors.manager_id) return;
                    }
                    if (this.step < 3) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                }
            };
        }
    </script>
@endsection
