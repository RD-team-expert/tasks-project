<div class="container">
<h2>tasks List</h2>
<a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Create tasks</a>
<table class="table">
    <thead>
        <tr><th>project_id</th><th>name</th><th>description</th><th>start_date</th><th>end_date</th><th>status</th><th>created_by</th></tr>
    </thead>
    <tbody>
        @foreach ($tasks as $item)
                <tr>
                    <td>{{$item->project_id}}</td>
<td>{{$item->name}}</td>
<td>{{$item->description}}</td>
<td>{{$item->start_date}}</td>
<td>{{$item->end_date}}</td>
<td>{{$item->status}}</td>
<td>{{$item->created_by}}</td>
<td>
                        <a href="{{ route('tasks.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('tasks.destroy', $item->id) }}" method="POST" style="display:inline;">
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