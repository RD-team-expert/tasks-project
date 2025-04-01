@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-xl">
        <h2 class="text-2xl font-bold mb-6">Create Project</h2>

        <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('start_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('end_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assign Manager (Admin Only) -->
            @if(auth()->user()->role === 'Admin')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assign Manager (Optional)</label>
                    <select name="manager_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">None</option>
                        @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Assigned Employees -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Assign Employees (Optional)</label>
                <select name="employees[]" multiple
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ collect(old('employees', []))->contains($user->id) ? 'selected' : '' }}>
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
                @error('employees')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Create Project</button>
            </div>
        </form>
    </div>
@endsection
