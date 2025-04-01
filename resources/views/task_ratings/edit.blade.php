<div class="container">
    <h2>Edit taskRating</h2>
    <form action="{{ route('task_ratings.update', $taskRating->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="task_id" class="form-label">task_id</label>
            <input type="text" class="form-control" name="task_id" value="{{old("task_id", $taskRating["task_id"])}}">
            @error("task_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="review" class="form-label">review</label>
            <input type="text" class="form-control" name="review" value="{{old("review", $taskRating["review"])}}">
            @error("review")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>