@extends('layouts.app')

@section('title', 'Create Question')

@section('content')
    <div x-data="questionForm()" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-4 text-black">Create New Question</h2>
        <p class="text-gray-600 mb-8">Fill in the details to create a new question</p>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 1 }">1. Question Details</div>
                <div class="flex-1 text-center" :class="{ 'text-[#28A745] font-bold': step >= 2 }">2. Review</div>
            </div>
            <div class="w-full bg-[#E0E0E0] rounded-full h-2">
                <div class="bg-[#28A745] h-2 rounded-full transition-all duration-300" :style="{ width: (step / 2) * 100 + '%' }"></div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('questions.store') }}" method="POST" x-ref="form">
            @csrf

            <!-- Step 1: Question Details -->
            <div x-show="step === 1">
                <!-- Question Text -->
                <div class="mb-6">
                    <label for="text" class="block text-sm font-semibold text-gray-600 mb-2">
                        Question Text <span class="text-red-600">*</span>
                        <span class="inline-block ml-1 text-gray-400 cursor-pointer" title="Enter the question text">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    </label>
                    <input
                        type="text"
                        id="text"
                        name="text"
                        x-model="form.text"
                        @input="validateText"
                        required
                        class="w-full bg-white border border-[#D3D3D3] rounded px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#28A745] transition"
                        aria-required="true"
                    >
                    <p x-show="errors.text" class="text-red-600 text-sm mt-2" x-text="errors.text"></p>
                    @error('text')
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

            <!-- Step 2: Review -->
            <div x-show="step === 2">
                <h3 class="text-lg font-semibold mb-4 text-black">Review Your Question</h3>
                <div class="mb-6 p-4 bg-[#F5F5F5] rounded-lg">
                    <p><strong>Question Text:</strong> <span x-text="form.text || 'Not provided'"></span></p>
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
        function questionForm() {
            return {
                step: 1,
                form: {
                    text: '{{ old('text') }}',
                },
                errors: {
                    text: ''
                },
                validateText() {
                    this.errors.text = this.form.text.trim() === '' ? 'Question text is required' : '';
                },
                nextStep() {
                    if (this.step === 1) {
                        this.validateText();
                        if (this.errors.text) return;
                    }
                    if (this.step < 2) this.step++;
                },
                prevStep() {
                    if (this.step > 1) this.step--;
                }
            };
        }
    </script>
@endsection
