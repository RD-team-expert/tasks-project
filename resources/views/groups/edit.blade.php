<div class="container">
    <h2>Edit group</h2>
    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control" name="name" value="{{old("name", $group["name"])}}">
            @error("name")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="manager_id" class="form-label">manager_id</label>
            <input type="text" class="form-control" name="manager_id" value="{{old("manager_id", $group["manager_id"])}}">
            @error("manager_id")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>