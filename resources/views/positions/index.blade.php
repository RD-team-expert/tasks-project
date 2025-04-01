<div class="container">
<h2>positions List</h2>
<a href="{{ route('positions.create') }}" class="btn btn-primary mb-3">Create positions</a>
<table class="table">
    <thead>
        <tr><th>title</th></tr>
    </thead>
    <tbody>
        @foreach ($positions as $item)
                <tr>
                    <td>{{$item->title}}</td>
<td>
                        <a href="{{ route('positions.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('positions.destroy', $item->id) }}" method="POST" style="display:inline;">
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