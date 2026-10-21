<div>
    <h1>Register</h1>

    <form method="POST" action="/register">
        @csrf

        <label>Name</label>
        <input name="name" value="{{ old('name') }}">
        @error('name')
        <p style="color:red">{{ $message }}</p> @enderror

        <label>Email</label>
        <input name="email" type="email" value="{{ old('email') }}">
        @error('email')
        <p style="color:red">{{ $message }}</p> @enderror

        <label>Password</label>
        <input name="password" type="password">
        @error('password')
        <p style="color:red">{{ $message }}</p> @enderror

        <label>Repeat password</label>
        <input name="password_confirmation" type="password">

        <button type="submit">Register</button>
    </form>
</div>