<div class="container">
<h2>groups List</h2>
<a href="{{ route('groups.create') }}" class="btn btn-primary mb-3">Create groups</a>
<table class="table">
    <thead>
        <tr><th>name</th><th>manager_id</th></tr>
    </thead>
    <tbody>
        @foreach ($groups as $item)
                <tr>
                    <td>{{$item->name}}</td>
<td>{{$item->manager_id}}</td>
<td>
                        <a href="{{ route('groups.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('groups.destroy', $item->id) }}" method="POST" style="display:inline;">
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