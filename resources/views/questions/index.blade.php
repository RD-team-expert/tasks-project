<div class="container">
<h2>questions List</h2>
<a href="{{ route('questions.create') }}" class="btn btn-primary mb-3">Create questions</a>
<table class="table">
    <thead>
        <tr><th>text</th></tr>
    </thead>
    <tbody>
        @foreach ($questions as $item)
                <tr>
                    <td>{{$item->text}}</td>
<td>
                        <a href="{{ route('questions.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('questions.destroy', $item->id) }}" method="POST" style="display:inline;">
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