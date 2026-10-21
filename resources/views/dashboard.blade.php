<h1>Hello, {{ auth()->user()->name }}!</h1>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Log out</button>
</form>

<h2>Add an expense</h2>

<form method="POST" action="/expenses">
    @csrf

    <label>Category</label>
    <select name="category_id">
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label>Amount (€)</label>
    <input name="amount" type="number" step="0.01" value="{{ old('amount') }}">
    @error('amount')
    <p style="color:red">{{ $message }}</p> @enderror

    <label>Description</label>
    <input name="description" value="{{ old('description') }}">

    <label>Date</label>
    <input name="spent_at" type="date" value="{{ old('spent_at', date('Y-m-d')) }}">
    @error('spent_at')
    <p style="color:red">{{ $message }}</p> @enderror

    <button type="submit">Add</button>
</form>

<h2>Totals by category</h2>

<ul>
    @forelse ($totals as $category => $total)
        <li>{{ $category }}: {{ number_format($total, 2) }} €</li>
    @empty
        <li>Nothing yet.</li>
    @endforelse
</ul>

<h2>My expenses</h2>

<form method="GET" action="/dashboard">
    <input name="search" value="{{ request('search') }}" placeholder="Search description">
    <button type="submit">Search</button>
    <a href="/dashboard">Clear</a>
</form>

<p>
    Export:
    <a href="/expenses/export/csv">CSV</a> |
    <a href="/expenses/export/json">JSON</a>
</p>

<table border="1">
    <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Description</th>
        <th>Amount</th>
    </tr>
    @forelse ($expenses as $expense)
        <tr>
            <td>{{ $expense->spent_at->format('Y-m-d') }}</td>
            <td>{{ $expense->category->name }}</td>
            <td>{{ $expense->description }}</td>
            <td>{{ $expense->amount }} €</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No expenses yet.</td>
        </tr>
    @endforelse
</table>