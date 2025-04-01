<div class="container">
<h2>project_ratings List</h2>
<a href="{{ route('project_ratings.create') }}" class="btn btn-primary mb-3">Create project_ratings</a>
<table class="table">
    <thead>
        <tr><th>project_id</th><th>review</th></tr>
    </thead>
    <tbody>
        @foreach ($project_ratings as $item)
                <tr>
                    <td>{{$item->project_id}}</td>
<td>{{$item->review}}</td>
<td>
                        <a href="{{ route('project_ratings.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('project_ratings.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>