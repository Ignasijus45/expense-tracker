@extends('layouts.app')

@section('content')

<h1 class="h3 mb-4">Statistics</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h5 mb-3">Spending per month</h2>
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Spending per week</h2>
            <select id="monthSelect" class="form-select w-auto"></select>
        </div>
        <canvas id="weeklyChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    let weeklyChart = null;

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

        const select = document.getElementById('monthSelect');

        for (const month of data.labels) {
            const option = document.createElement('option');
            option.value = month;
            option.textContent = month;
            select.appendChild(option);
        }

        const lastMonth = data.labels[data.labels.length - 1];
        select.value = lastMonth;
        loadWeekly(lastMonth);
    }

    async function loadWeekly(month) {
        const response = await fetch('/api/stats/weekly?month=' + month, {
            headers: { 'Accept': 'application/json' },
        });

        const data = await response.json();

        const labels = data.labels.map(week => 'Week ' + week);

        if (weeklyChart !== null) {
            weeklyChart.destroy();
        }

        weeklyChart = new Chart(document.getElementById('weeklyChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: data.datasets,
            },
            options: {
                scales: {
                    x: { stacked: true },
                    y: { stacked: true },
                },
            },
        });
    }

    document.getElementById('monthSelect').addEventListener('change', (event) => {
        loadWeekly(event.target.value);
    });

    loadMonthly();
</script>
@endpush