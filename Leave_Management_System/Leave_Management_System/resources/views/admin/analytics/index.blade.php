@extends('layouts.admin')

@section('content')
<style>
    body {
        font-family: "Century Gothic", sans-serif;
        background-color: #f8fafc;
        color: #333;
        margin: 0;
        padding: 0;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e4e7ec;
    }
    .page-header h2 {
        font-weight: 700;
        font-size: 2.4rem;
        color: #1e293b;
        margin-bottom: 6px;
        margin: 0;
    }
    .filter-form select {
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 1rem;
    }
    .metrics-row {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 40px;
    }
    .metrics-col {
        flex: 1 1 calc(25% - 24px);
        min-width: 240px;
    }
    .card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 12px 25px rgba(99, 99, 99, 0.12);
        padding: 20px;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 20px 40px rgba(99, 99, 99, 0.18);
        transform: translateY(-4px);
    }
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .card-icon {
        font-size: 24px;
        padding: 10px;
        border-radius: 12px;
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.12);
    }
    .bg-blue-100 { background-color: #b3d7e6ff; }
    .text-blue-600 { color: #2563eb; }
    .bg-yellow-100 { background-color: #ebdfabff; }
    .text-yellow-600 { color: #d97706; }
    .bg-green-100 { background-color: #9acaaaff; }
    .text-green-600 { color: #22c55e; }
    .bg-pink-100 { background-color: #eca6acff; }
    .text-pink-600 { color: #360310ff; }
    .card h5 {
        font-weight: 600;
        color: #334155;
        font-size: 1.1rem;
        margin: 0 0 0.3rem;
    }
    .card h3 {
        font-weight: 700;
        font-size: 2.2rem;
        color: #0f172a;
        margin: 0 0 0.4rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card small {
        font-size: 0.9rem;
        font-weight: 600;
    }
    .text-success { color: #22c55e !important; }
    .text-warning { color: #f59e0b !important; }
    .text-primary { color: #2563eb !important; }
    .text-muted { color: #64748b !important; }
    .stats-row {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 40px;
    }
    .chart-card {
        flex: 1 1 calc(50% - 24px);
        min-width: 360px;
    }
    .table-card {
        flex: 1 1 100%;
    }
    .progress {
        height: 14px;
        border-radius: 14px;
        background-color: #e2e8f0;
        margin-top: 4px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        width: 100%;
    }
    .progress-bar {
        border-radius: 14px;
        transition: width 0.4s ease;
        height: 100%;
    }
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    th {
        font-weight: 600;
        color: #475569;
        font-size: 1rem;
        padding-bottom: 12px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    td {
        background: #f1f5f9;
        padding: 12px 15px;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #334155;
        vertical-align: middle;
    }
    h2 {
        font-weight: 700;
        font-size: 2.4rem;
        color: #2c3e50;
        margin: 0 0 20px 0;
        border-left: 6px solid #138056ff;
        padding-left: 15px;
        letter-spacing: 0.04em;
    }
    tr:hover td {
        background-color: #e0e7ff;
    }
    .leave-type-entry {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    .leave-type-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        font-size: 1rem;
    }
    .leave-type-name {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .leave-type-icon {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: inline-block;
    }
    table.table tbody tr {
        background-color: #f9fafb;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: background-color 0.3s, box-shadow 0.3s;
    }
    table.table tbody tr:hover {
        background-color: #e0e7ff;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
    }
    table.table thead tr th {
        border-bottom: none !important;
    }
    table.table tbody tr td {
        border: none !important;
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <h2>Analytics & Insights</h2>
        <form method="GET" class="filter-form" id="monthFilterForm">
            <select name="month" onchange="document.getElementById('monthFilterForm').submit()">
                <!-- <option value="">All Time</option> -->
                <option value="this_month" {{ $monthFilter == 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="this_quarter" {{ $monthFilter == 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                <option value="this_year" {{ $monthFilter == 'this_year' ? 'selected' : '' }}>This Year</option>
                @foreach($months as $month)
                    <!-- <option value="{{ $month }}" {{ $monthFilter == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}
                    </option> -->
                @endforeach
            </select>
        </form>
    </div>

    <div class="metrics-row">
        <div class="metrics-col">
            <div class="card">
                <div class="card-header">
                    <h5>Total Leaves</h5>
                    <div class="card-icon bg-blue-100 text-blue-600"><i class="fas fa-calendar-alt"></i></div>
                </div>
                <h3>{{ $totalLeaves }}</h3>
                <small class="text-success">{{ $approvalRate }}% approved</small>
            </div>
        </div>
        <div class="metrics-col">
            <div class="card">
                <div class="card-header">
                    <h5>Pending Requests</h5>
                    <div class="card-icon bg-yellow-100 text-yellow-600"><i class="fas fa-hourglass-half"></i></div>
                </div>
                <h3>{{ $pendingRequests }}</h3>
                <small class="text-warning">Awaiting approval</small>
            </div>
        </div>
        <div class="metrics-col">
            <div class="card">
                <div class="card-header">
                    <h5>Active Employees</h5>
                    <div class="card-icon bg-green-100 text-green-600"><i class="fas fa-user-check"></i></div>
                </div>
                <h3>{{ $activeEmployees }}</h3>
                <small class="text-muted">of {{ $totalEmployees }} total</small>
            </div>
        </div>
        <div class="metrics-col">
            <div class="card">
                <div class="card-header">
                    <h5>Approval Rate</h5>
                    <div class="card-icon bg-pink-100 text-pink-600"><i class="fas fa-chart-line"></i></div>
                </div>
                <h3>{{ $approvalRate }}%</h3>
                <small class="text-primary">{{ $approvedCount }} approved</small>
            </div>
        </div>
    </div>

    <div class="stats-row">
        <div class="chart-card">
            <div class="card">
                <h5><i class="fas fa-chart-pie me-2 text-primary"></i>Leaves by Type</h5>
                <hr>
                @foreach ($leavesByType as $type)
                    @php
                    $percentage = $totalLeaves > 0 ? round(($type->total / $totalLeaves) * 100, 1) : 0;
                    $colors = [
                        'Sick' => '#182f33ff',
                        'Annual' => '#fa0542ff',
                        'Casual' => '#a855f7',
                        'Unpaid' => '#561164ff',
                        'Maternity' => '#ec4899',
                        'Study' => '#14b8a6',
                        'Family Responsibility' => '#db5454ff',
                        'Paternity' => '#12c92aff',
                        'Bereavement' => '#b8e906ff',
                        'Compassionate' => '#4a4b1bff',
                    ];
                    $color = $colors[$type->leave_type] ?? '#6b7280';
                    @endphp
                    <div class="leave-type-entry">
                        <div class="leave-type-label">
                            <div class="leave-type-name" style="color: {{ $color }};">
                                <span class="leave-type-icon" style="background-color: {{ $color }}"></span>
                                {{ ucfirst($type->leave_type) }}
                            </div>
                            <span style="color: {{ $color }};">{{ $type->total }} ({{ $percentage }}%)</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="chart-card">
            <div class="card">
                <h5><i class="fas fa-chart-pie me-2 text-success"></i>Leaves by Department</h5>
                <hr>
                <canvas id="designationPieChart" style="max-height: 370px;"></canvas>
            </div>
        </div>
    </div>

    <div class="stats-row">
        <div class="table-card">
            <div class="card">
                <h5><i class="fas fa-trophy me-2 text-warning"></i>Top Leave Takers</h5>
                <hr>
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Employee</th>
                            <th>Total Leaves</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topLeaveTakers as $index => $taker)
                            @php
                                $percentage = $totalForTop > 0 ? round(($taker->total / $totalForTop) * 100, 1) : 0;
                                $percentageColor = '#a80d55ff';
                            @endphp
                            <tr style="background-color: #f9fafb; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: background-color 0.3s, box-shadow 0.3s;">
                                <td style="font-weight: 600; color: #64748b;">#{{ $index + 1 }}</td>
                                <td style="font-weight: 600; color: #334155;">{{ $taker->user->first_name ?? 'Unknown' }}</td>
                                <td style="font-weight: 600; color: #334155;">{{ $taker->total }} leaves</td>
                                <td>
                                    <div class="progress" style="height: 12px; border-radius: 12px; margin-bottom: 6px;">
                                        <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $percentageColor }};"></div>
                                    </div>
                                    <small style="font-weight: 700; color: {{ $percentageColor }};">{{ $percentage }}%</small>
                                </td>
                            </tr>
                        @endforeach
                        @if ($topLeaveTakers->isEmpty())
                            <tr><td colspan="4" class="text-center text-muted p-4">No data available</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('designationPieChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($designationLabels),
            datasets: [{
                data: @json($designationData),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b',
                    '#ef4444', '#8b5cf6', '#ec4899',
                    '#14b8a6', '#f97316', '#6b7280'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
