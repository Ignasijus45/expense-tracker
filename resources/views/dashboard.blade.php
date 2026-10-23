@extends('layouts.app')

@section('content')

<h1 class="h3 mb-4">Hello, {{ auth()->user()->name }} 👋</h1>

<div class="row">

    <div class="col-lg-4">

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Add an expense</h2>

                <form method="POST" action="/expenses">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount (€)</label>
                        <input name="amount" type="number" step="0.01" value="{{ old('amount') }}" class="form-control">
                        @error('amount') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input name="description" value="{{ old('description') }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input name="spent_at" type="date" value="{{ old('spent_at', date('Y-m-d')) }}" class="form-control">
                        @error('spent_at') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Add expense</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Totals by category</h2>

                @forelse ($totals as $category => $total)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $category }}</span>
                        <strong>{{ number_format($total, 2) }} €</strong>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nothing yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <div class="col-lg-8">

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h2 class="h5">My expenses</h2>
                    <div>
                        <a href="/expenses/export/csv" class="btn btn-outline-secondary btn-sm">CSV</a>
                        <a href="/expenses/export/json" class="btn btn-outline-secondary btn-sm">JSON</a>
                    </div>
                </div>

                <form method="GET" action="/dashboard" class="d-flex mb-3">
                    <input name="search" value="{{ request('search') }}" placeholder="Search description" class="form-control me-2">
                    <button type="submit" class="btn btn-secondary me-2">Search</button>
                    <a href="/dashboard" class="btn btn-light">Clear</a>
                </form>

                <table class="table table-hover">
                    <tr class="table-light">
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                    </tr>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->spent_at->format('Y-m-d') }}</td>
                            <td><span class="badge bg-secondary">{{ $expense->category->name }}</span></td>
                            <td>{{ $expense->description }}</td>
                            <td class="text-end">{{ $expense->amount }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No expenses yet.</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>

    </div>

</div>

@endsection