<div class="container">
    <h2>user Details</h2>
     <p><strong>username:</strong> {{ $user ->username }}</p>
<p><strong>email:</strong> {{ $user ->email }}</p>
<p><strong>email_verified_at:</strong> {{ $user ->email_verified_at }}</p>
<p><strong>password:</strong> {{ $user ->password }}</p>
<p><strong>role:</strong> {{ $user ->role }}</p>
<p><strong>position_id:</strong> {{ $user ->position_id }}</p>
<p><strong>remember_token:</strong> {{ $user ->remember_token }}</p>

</div>