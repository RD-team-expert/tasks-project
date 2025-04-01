<div class="container">
    <h2>Edit task</h2>
    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="project_id" class="form-label">project_id</label>
            <input type="text" class="form-control" name="project_id" value="{{old("project_id", $task["project_id"])}}">
            @error("project_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control" name="name" value="{{old("name", $task["name"])}}">
            @error("name")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="description" class="form-label">description</label>
            <input type="text" class="form-control" name="description" value="{{old("description", $task["description"])}}">
            @error("description")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="start_date" class="form-label">start_date</label>
            <input type="text" class="form-control" name="start_date" value="{{old("start_date", $task["start_date"])}}">
            @error("start_date")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="end_date" class="form-label">end_date</label>
            <input type="text" class="form-control" name="end_date" value="{{old("end_date", $task["end_date"])}}">
            @error("end_date")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="status" class="form-label">status</label>
            <input type="text" class="form-control" name="status" value="{{old("status", $task["status"])}}">
            @error("status")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="created_by" class="form-label">created_by</label>
            <input type="text" class="form-control" name="created_by" value="{{old("created_by", $task["created_by"])}}">
            @error("created_by")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>