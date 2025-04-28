@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <div x-data="projectForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Edit Project</h2>
        <p class="text-gray-600 mb-8">Update the project details</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. Info</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Schedule</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 3 }">3. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 3) * 100 + '%' }"></div>
            </div>
        </div>

        <form action="{{ route('projects.update', $project->id) }}" method="POST" x-ref="form">
            @csrf
            @method('PATCH')

            <!-- Step 1: Basic Info -->
            <div x-show="step === 1" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Project Name</label>
                    <input type="text" name="name" x-model="form.name"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-[#28A745]">
                    @error('name') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                    <textarea name="description" x-model="form.description" rows="3"
                              class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-[#28A745]">{{ old('description', $project->description) }}</textarea>
                    @error('description') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="nextStep"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">Next</button>
                </div>
            </div>

            <!-- Step 2: Schedule -->
            <div x-show="step === 2" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                    <input type="date" name="start_date" x-model="form.start_date"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-[#28A745]">
                    @error('start_date') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">End Date</label>
                    <input type="date" name="end_date" x-model="form.end_date"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-[#28A745]">
                    @error('end_date') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                @if(auth()->user()->role === 'Admin')
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Manager</label>
                        <select name="manager_id" x-model="form.manager_id"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-[#28A745]">
                            <option value="">Select Manager</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id', $project->manager_id) == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('manager_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div class="flex justify-between">
                    <button type="button" @click="prevStep"
                            class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold">Previous</button>
                    <button type="button" @click="nextStep"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">Next</button>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div x-show="step === 3" class="space-y-6">
                <div class="bg-[#F5F5F5] p-4 rounded space-y-2">
                    <h3 class="text-lg font-semibold mb-2">Review Your Changes</h3>
                    <p><strong>Name:</strong> <span x-text="form.name || 'N/A'"></span></p>
                    <p><strong>Description:</strong> <span x-text="form.description || 'N/A'"></span></p>
                    <p><strong>Start Date:</strong> <span x-text="form.start_date || 'Not set'"></span></p>
                    <p><strong>End Date:</strong> <span x-text="form.end_date || 'Not set'"></span></p>
                    <template x-if="form.manager_id">
                        <p><strong>Manager:</strong> <span x-text="getManagerName(form.manager_id)"></span></p>
                    </template>
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="prevStep"
                            class="px-6 py-2 bg-[#E0E0E0] text-gray-600 rounded hover:bg-[#D3D3D3] transition font-semibold">Previous</button>
                    <button type="submit"
                            class="px-6 py-2 bg-[#28A745] text-white rounded hover:bg-[#218838] transition font-semibold">Confirm and Update</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function projectForm() {
            return {
                step: 1,
                form: {
                    name: '{{ old('name', $project->name) }}',
                    description: '{{ old('description', $project->description) }}',
                    start_date: '{{ old('start_date', $project->start_date) }}',
                    end_date: '{{ old('end_date', $project->end_date) }}',
                    manager_id: '{{ old('manager_id', $project->manager_id) }}',
                },
                managers: @json($managers),
                nextStep() { if (this.step < 3) this.step++ },
                prevStep() { if (this.step > 1) this.step-- },
                getManagerName(id) {
                    const manager = this.managers.find(m => m.id == id);
                    return manager ? manager.username : 'Not assigned';
                }
            }
        }
    </script>
@endsection
