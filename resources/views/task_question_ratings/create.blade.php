<div class="container">
    <h2>Create task_question_ratings</h2>
    <form action="{{ route('task_question_ratings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="task_rating_id" class="form-label">task_rating_id</label>
            <input type="text" class="form-control" name="task_rating_id" value="{{old("task_rating_id")}}">
            @error("task_rating_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="question_id" class="form-label">question_id</label>
            <input type="text" class="form-control" name="question_id" value="{{old("question_id")}}">
            @error("question_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="rating" class="form-label">rating</label>
            <input type="text" class="form-control" name="rating" value="{{old("rating")}}">
            @error("rating")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>