@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-2xl font-bold mb-4">Projects List</h2>
        <a href="{{ route('projects.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition duration-150 mb-4 inline-block">Create Project</a>

        @if(!$projects || $projects->isEmpty())
            <p class="text-center text-gray-600">No projects available.</p>
        @else
            <table class="w-full bg-white shadow-md rounded-lg border">
                <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="p-3 text-left font-semibold text-gray-700">Description</th>
                    <th class="p-3 text-left font-semibold text-gray-700">Start Date</th>
                    <th class="p-3 text-left font-semibold text-gray-700">End Date</th>
                    <th class="p-3 text-left font-semibold text-gray-700">Created By</th>
                    <th class="p-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($projects as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $item->name }}</td>
                        <td class="p-3">{{ $item->description }}</td>
                        <td class="p-3">{{ $item->start_date }}</td>
                        <td class="p-3">{{ $item->end_date }}</td>
                        <td class="p-3">{{ $item->creator->username ?? 'N/A' }}</td>
                        <td class="p-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('projects.edit', $item->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-150">Edit</a>
                                <form action="{{ route('projects.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-150" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
@endsection
