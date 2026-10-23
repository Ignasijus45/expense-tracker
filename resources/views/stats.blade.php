@extends('layouts.app')

@section('content')

<h1 class="h3 mb-4">Statistics</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <pre id="output">Loading...</pre>
    </div>
</div>

@endsection

@push('scripts')
<script>
    async function loadMonthly() {
        const response = await fetch('/api/stats/monthly', {
            headers: { 'Accept': 'application/json' },
        });

        const data = await response.json();

        document.getElementById('output').textContent = JSON.stringify(data, null, 2);
    }

    loadMonthly();
</script>
@endpush
