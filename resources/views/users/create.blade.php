@extends('layouts.app')

@section('content')
    <div class="container max-w-xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Create User</h2>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            <input name="username" placeholder="Username" value="{{ old('username') }}" class="w-full border p-2 rounded">
            <input name="email" placeholder="Email" value="{{ old('email') }}" class="w-full border p-2 rounded">
            <input name="password" placeholder="Password" type="password" class="w-full border p-2 rounded">

            <select name="role" class="w-full border p-2 rounded">
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
                <option value="Employee">Employee</option>
            </select>

            <select name="manager_id" class="w-full border p-2 rounded">
                <option value="">Assign Manager (Optional)</option>
                @foreach(\App\Models\User::where('role', 'Manager')->get() as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->username }}</option>
                @endforeach
            </select>

            <!-- ✅ Add Group Selection -->
            <select name="group_id" class="w-full border p-2 rounded">
                <option value="">Assign Group (Optional)</option>
                @foreach(\App\Models\Group::all() as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>

            <button class="bg-green-500 text-white px-4 py-2 rounded" type="submit">Create</button>
        </form>
    </div>
@endsection
