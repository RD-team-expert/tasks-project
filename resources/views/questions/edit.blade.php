<div class="container">
    <h2>Edit question</h2>
    <form action="{{ route('questions.update', $question->id) }}" method="POST">
        @csrf
        @method("PATCH")
        <div class="mb-3">
            <label for="text" class="form-label">text</label>
            <input type="text" class="form-control" name="text" value="{{old("text", $question["text"])}}">
            @error("text")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>