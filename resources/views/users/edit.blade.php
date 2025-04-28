@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div x-data="userForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Edit User</h2>
        <p class="text-gray-600 mb-8">Update the details for the user</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. User Info</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 2) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" x-ref="form">
            @csrf
            @method('PUT')

            <!-- Step 1 -->
            <div x-show="step === 1">
                <div class="mb-6">
                    <label for="username" class="block text-sm font-semibold text-gray-600 mb-2">Username <span class="text-red-600">*</span></label>
                    <input type="text" id="username" name="username" x-model="form.username"
                           @input="validateUsername"
                           class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                    <p x-show="errors.username" class="text-red-600 text-sm mt-2" x-text="errors.username"></p>
                    @error('username') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-600 mb-2">Email <span class="text-red-600">*</span></label>
                    <input type="email" id="email" name="email" x-model="form.email"
                           @input="validateEmail"
                           class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745] transition">
                    <p x-show="errors.email" class="text-red-600 text-sm mt-2" x-text="errors.email"></p>
                    @error('email') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="role" class="block text-sm font-semibold text-gray-600 mb-2">Role</label>
                    <select id="role" name="role" x-model="form.role"
                            class="w-full border border-[#D3D3D3] rounded px-3 py-2 text-gray-800">
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Employee">Employee</option>
                    </select>
                    @error('role') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="nextStep"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2 -->
            <div x-show="step === 2">
                <h3 class="text-lg font-semibold mb-4 text-black">Review Your Changes</h3>
                <div class="mb-6 p-4 bg-[#F5F5F5] rounded-lg space-y-2">
                    <p><strong>Username:</strong> <span x-text="form.username || 'Not provided'"></span></p>
                    <p><strong>Email:</strong> <span x-text="form.email || 'Not provided'"></span></p>
                    <p><strong>Role:</strong> <span x-text="form.role || 'Not selected'"></span></p>
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
        function userForm() {
            return {
                step: 1,
                form: {
                    username: '{{ old('username', $user->username) }}',
                    email: '{{ old('email', $user->email) }}',
                    role: '{{ old('role', $user->role) }}'
                },
                errors: {
                    username: '',
                    email: ''
                },
                validateUsername() {
                    this.errors.username = this.form.username.trim() === '' ? 'Username is required' : '';
                },
                validateEmail() {
                    this.errors.email = this.form.email.trim() === '' ? 'Email is required' : '';
                },
                nextStep() {
                    this.validateUsername();
                    this.validateEmail();
                    if (!this.errors.username && !this.errors.email) {
                        this.step++;
                    }
                },
                prevStep() {
                    this.step--;
                }
            };
        }
    </script>
@endsection
