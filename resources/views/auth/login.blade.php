<div>
    <h1>Log in</h1>

    <form method="POST" action="/login">
        @csrf

        <label>Email</label>
        <input name="email" type="email" value="{{ old('email') }}">
        @error('email')
        <p style="color:red">{{ $message }}</p> @enderror

        <label>Password</label>
        <input name="password" type="password">

        <button type="submit">Log in</button>
    </form>

    <p>No account? <a href="/register">Register</a></p>
</div>