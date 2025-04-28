@extends('layouts.app')

@section('title', 'Edit Group')

@section('content')
    <div x-data="groupForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Edit Group</h2>
        <p class="text-gray-600 mb-8">Update the details for the group</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. Group Details</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 2) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('groups.update', $group->id) }}" method="POST" x-ref="form">
            @csrf
            @method('PATCH')

            <!-- Step 1 -->
            <div x-show="step === 1">
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-600 mb-2">Group Name <span class="text-red-600">*</span></label>
                    <input type="text" id="name" name="name" x-model="form.name"
                           @input="validateName"
                           class="w-full border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:ring-2 focus:ring-[#28A745]">
                    <p x-show="errors.name" class="text-red-600 text-sm mt-2" x-text="errors.name"></p>
                    @error('name') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="manager_id" class="block text-sm font-semibold text-gray-600 mb-2">Manager</label>
                    <select id="manager_id" name="manager_id" x-model="form.manager_id"
                            class="w-full border border-[#D3D3D3] rounded px-3 py-2 text-gray-800">
                        <option value="">None</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $group->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="nextStep"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2: Review -->
            <div x-show="step === 2">
                <h3 class="text-lg font-semibold mb-4 text-black">Review Your Changes</h3>
                <div class="mb-6 p-4 bg-[#F5F5F5] rounded-lg space-y-2">
                    <p><strong>Name:</strong> <span x-text="form.name || 'Not provided'"></span></p>
                    <p><strong>Manager ID:</strong> <span x-text="form.manager_id || 'None'"></span></p>
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
        function groupForm() {
            return {
                step: 1,
                form: {
                    name: '{{ old('name', $group->name) }}',
                    manager_id: '{{ old('manager_id', $group->manager_id) }}',
                },
                errors: {
                    name: ''
                },
                validateName() {
                    this.errors.name = this.form.name.trim() === '' ? 'Group name is required' : '';
                },
                nextStep() {
                    this.validateName();
                    if (!this.errors.name) {
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
