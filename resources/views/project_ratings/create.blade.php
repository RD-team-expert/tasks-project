<div class="container">
    <h2>Create project_ratings</h2>
    <form action="{{ route('project_ratings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="project_id" class="form-label">project_id</label>
            <input type="text" class="form-control" name="project_id" value="{{old("project_id")}}">
            @error("project_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="review" class="form-label">review</label>
            <input type="text" class="form-control" name="review" value="{{old("review")}}">
            @error("review")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>