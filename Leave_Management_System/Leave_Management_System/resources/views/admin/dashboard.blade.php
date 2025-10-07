@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    body, html {
        margin: 0;
        padding: 0;
    }
    .admin-container {
        font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
        width: 1300px;
        padding: 20px 24px 40px;
        min-height: 100vh;
        max-width: 2000px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(163, 71, 71, 0.08);
        margin: 0 auto;
        user-select: none;
    }
    h1 {
        font-weight: 700;
        font-size: 40px;
        color: #2c3e50;
        margin: 0 0 24px 0;
        border-left: 6px solid #138056ff;
        padding-left: 15px;
        letter-spacing: 0.04em;
    }
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    .card {
        background: white;
        border-radius: 16px;
        padding: 20px 16px 16px;
        box-shadow: 0 6px 16px rgba(8, 8, 8, 0.15);
        color: black;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 160px;
        cursor: move;
        transition: box-shadow 0.3s ease;
        user-select: none;
        position: relative;
        text-decoration: none;
    }
    .card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }
    /* Colored text matching card type */
    .card-pending .card-title, .card-pending .card-value { color: #facc15; }
    .card-approved .card-title, .card-approved .card-value { color: #10b981; }
    .card-rejected .card-title, .card-rejected .card-value { color: #ef4444; }

    .card-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin: 6px 0 12px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-value {
        font-weight: bold;
        font-size: 2.2rem;
        margin-top: 0;
    }
    .dash-charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-top: 10px;
    }
    .chart-card {
        background: white;
        padding: 24px 20px;
        border-radius: 24px;
        box-shadow: 0 6px 22px rgba(67, 83, 255, 0.12);
        border: 1px solid #dbe3f0;
        color: #222;
    }
    canvas {
        width: 100% !important;
        max-height: 400px;
    }
</style>
 <h1>Admin Dashboard</h1>
<div class="admin-container">
   
    <div class="cards" id="cardsContainer" role="list">
        <a href="{{ route('admin.requests', ['filter' => 'pending']) }}" role="listitem" aria-label="Pending leave requests" class="card card-pending" draggable="true">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#facc15" viewBox="0 0 24 24" stroke-width="2" stroke="#facc15" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                    <circle cx="12" cy="12" r="10" stroke="#facc15" stroke-width="2" fill="none"/>
                </svg>
                Pending Requests
            </div>
            <div class="card-value">{{ $pendingRequestsCount }}</div>
        </a>

        <a href="{{ route('admin.calendar') }}" role="listitem" aria-label="Employees on leave today" class="card card-approved" draggable="true">
            <div class="card-title">
                On Leave Today
            </div>
            <div class="card-value">{{ $onLeaveTodayCount }}</div>
        </a>

        <a href="{{ route('admin.employees') }}" role="listitem" aria-label="Active employees count" class="card card-approved" draggable="true">
            <div class="card-title">
                Active Employees
            </div>
            <div class="card-value">{{ $activeEmployeesCount }}</div>
        </a>

        <a href="{{ route('admin.analytics') }}" role="listitem" aria-label="This month analytics" class="card card-approved" draggable="true">
            <div class="card-title">
                This Month
            </div>
            <div class="card-value">{{ $thisMonthCount }}</div>
        </a>
    </div>

    <div class="dash-charts-grid">
        <div class="chart-card">
            <h3>Leave Requests by Type</h3>
            <canvas id="leaveDoughnut"></canvas>
        </div>
        <div class="chart-card">
            <h3>Monthly Leave Requests</h3>
            <canvas id="monthlyBar"></canvas>
        </div>
      <div class="chart-card">
    <h3>Leave Requests by Status</h3>
    <canvas id="statusPie"></canvas>
</div>


    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('cardsContainer');
        let dragged;

        container.addEventListener('dragstart', (e) => {
            dragged = e.target;
            e.target.style.opacity = 0.5;
            e.dataTransfer.effectAllowed = "move";
        });

        container.addEventListener('dragend', (e) => {
            e.target.style.opacity = "";
        });

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = "move";
            const target = e.target.closest('.card');
            if(target && target !== dragged) {
                const rect = target.getBoundingClientRect();
                const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
                container.insertBefore(dragged, next && target.nextSibling || target);
            }
        });

        new Chart(document.getElementById('leaveDoughnut'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($leaveTypeCounts)),
                datasets: [{
                    data: @json(array_values($leaveTypeCounts)),
                    backgroundColor: [
                        '#740820ff',
                        '#284436ff',
                        '#c6f810ff',
                        '#194d4dff',
                        '#1e103aff',
                        '#361f08ff'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 14, weight: '600' }, color: '#2c3e50' } },
                    tooltip: { bodyFont: { size: 14 }, titleFont: { weight: '700' } }
                }
            }
        });
      
       new Chart(document.getElementById('statusPie'), {
    type: 'pie',
    data: {
        labels: ['Pending', 'Rejected', 'Approved'],
        datasets: [{
            data: [
                {{ $statusCounts['Pending'] ?? 0 }},
                {{ $statusCounts['Rejected'] ?? 0 }},
                {{ $statusCounts['Approved'] ?? 0 }}
            ],
            backgroundColor: [
                '#facc15', // yellow for pending
                '#ef4444', // red for rejected
                '#10b981'  // green for approved
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 14, weight: '600' }, color: '#2c3e50' } },
            tooltip: { bodyFont: { size: 14 }, titleFont: { weight: '700' } }
        }
    }
});



        new Chart(document.getElementById('monthlyBar'), {
            type: 'bar',
            data: {
                labels: @json($barLabels),
                datasets: [{
                    label: 'Total Requests',
                    data: @json($barData),
                    backgroundColor: '#075548ff',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { bodyFont: { size: 14 }, titleFont: { weight: '700' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endsection
