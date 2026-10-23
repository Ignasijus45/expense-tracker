@extends('layouts.app')

@section('content')

<h1 class="h3 mb-4">Statistics</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h5 mb-3">Spending per month</h2>
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    async function loadMonthly() {
        const response = await fetch('/api/stats/monthly', {
            headers: { 'Accept': 'application/json' },
        });

        const data = await response.json();

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    x: { stacked: true },
                    y: { stacked: true },
                },
            },
        });
    }

    loadMonthly();
</script>
@endpush