@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Group</h2>
        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Group Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="manager_id" class="form-label">Select Manager</label>
                <select class="form-control" id="manager_id" name="manager_id" required>
                    @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->username ?? $manager->name }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="employees" class="form-label">Select Employees (Optional)</label>
                <select class="form-control" id="employees" name="employees[]" multiple>
                    @foreach(\App\Models\User::where('role', 'Employee')->get() as $employee)
                        <option value="{{ $employee->id }}" {{ in_array($employee->id, old('employees', [])) ? 'selected' : '' }}>
                            {{ $employee->username ?? $employee->name }}
                        </option>
                    @endforeach
                </select>
                @error('employees')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
