@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>My Projects</h1>

        @if($projects->isEmpty())
            <p>You have no assigned projects.</p>
        @else
            @foreach($projects as $project)
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>{{ $project->name }}</h2>
                    </div>
                    <div class="card-body">
                        <p>{{ $project->description }}</p>
                        <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
                        <p><strong>End Date:</strong> {{ $project->end_date }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('projects.myProjects') }}" class="btn btn-primary">View My Projects</a>
                    </div>
                </div>
            @endforeach
            
            {{ $projects->links() }} <!-- Add pagination links -->
        @endif
    </div>
@endsection