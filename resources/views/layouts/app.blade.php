<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">Expense Tracker</a>

            @auth
                <div>
                    <a href="/dashboard" class="btn btn-dark btn-sm">Dashboard</a>
                    <a href="/stats" class="btn btn-dark btn-sm">Statistics</a>
                </div>

                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Log out</button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>