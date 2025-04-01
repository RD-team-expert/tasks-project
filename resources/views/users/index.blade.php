@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Users List</h2>

        <!-- Role-based Create Button -->
        @if(auth()->user()->role === 'Admin')
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create User</a>
        @elseif(auth()->user()->role === 'Manager')
            <a href="{{ route('users.createEmployee') }}" class="btn btn-primary mb-3">Add Employee</a>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Email Verified At</th>
                <th>Role</th>
                <th>Position ID</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $item)
                <tr>
                    <td>{{ $item->username }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->email_verified_at ?? 'Not Verified' }}</td>
                    <td>{{ $item->role }}</td>
                    <td>{{ $item->position_id ?? 'N/A' }}</td>
                    <td>
                        @if(auth()->user()->role === 'Admin')
                            <a href="{{ route('users.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No users found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{ $users->links() }}
    </div>
@endsection
