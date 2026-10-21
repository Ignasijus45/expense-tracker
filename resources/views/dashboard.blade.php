<div>
    <h1>Hello, {{ auth()->user()->name }}!</h1>

    <p>You are logged in.</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Log out</button>
    </form>
</div>