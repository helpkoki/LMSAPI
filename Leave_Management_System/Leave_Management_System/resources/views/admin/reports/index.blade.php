@extends('layouts.admin')

@section('content')
<style>
    body {
        font-family: "Century Gothic", sans-serif;
        background: #f8fafc;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .page-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e4e7ec;
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

    /* Date range filter form styling */
    form.filter-form {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    form.filter-form input[type="date"] {
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 1rem;
    }
    form.filter-form button {
        padding: 6px 15px;
        border-radius: 4px;
        border: none;
        background:#138056ff;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }
    form.filter-form button:hover {
        background:#0f6b4f;
    }

    /* Cards styles */
    .stats-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        flex: 1 1 calc(20% - 20px);
        min-width: 200px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 12px 25px rgba(99, 99, 99, 0.12);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: box-shadow 0.3s, transform 0.3s;
    }
    .stat-card:hover {
        box-shadow: 0 20px 40px rgba(99, 99, 99, 0.18);
        transform: translateY(-4px);
    }
    .stat-icon {
        font-size: 24px;
        padding: 10px;
        background: #e0f2fe;
        color: #0891b2;
        border-radius: 50%;
        margin-bottom: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .stat-title {
        font-weight: 600; font-size: 1.1rem; margin-bottom: 8px; color: #334155;
    }
    .stat-number {
        font-weight: 700; font-size: 2rem; margin-bottom: 4px; color: #0f172a;
    }
    .stat-small {
        font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px;
    }

    /* Recent Leave Requests Table styles */
    .recent-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }
    .recent-table th {
        font-weight: 600;
        color: #475569;
        font-size: 1rem;
        padding-bottom: 10px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    .recent-table td {
        background: #f1f5f9;
        padding: 12px 15px;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #334155;
        vertical-align: middle;
    }
    .recent-table tbody tr:hover {
        background-color: #e0e7ff;
        box-shadow: 0 4px 12px rgba(59,130,246,0.2);
    }
    /* Status label styles */
    .status-label {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #fff;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    .status-pending { background-color: #facc15; color: #fff; }
    .status-rejected { background-color: #ef4444; color: #fff; }
    .status-approved { background-color: #22c55e; color: #fff; }

</style>

<div class="container-fluid">

    <!-- Heading & Filters -->
    <div class="page-header">
        <h2>Leave Requests Report</h2>
        <form method="GET" class="filter-form">
            <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date" required>
            <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date" required>
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-list-ul"></i></div>
            <div class="stat-title">Total Requests</div>
            <div class="stat-number">{{ $totalRequests ?? 0 }}</div>
            <div class="stat-small" style="color:#22c55e;">
                <i class="fas fa-check-circle"></i> Days Approved: {{ $daysApproved ?? 0 }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#ffe4b5; color:#c77800;"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-title">Pending Requests</div>
            <div class="stat-number">{{ $pendingRequests ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#c6f6d5; color:#276749;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-title">Approved Requests</div>
            <div class="stat-number">{{ $approvedRequests ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fecaca; color:#b91c1c;"><i class="fas fa-ban"></i></div>
            <div class="stat-title">Rejected Requests</div>
            <div class="stat-number">{{ $rejectedRequests ?? 0 }}</div>
        </div>
    </div>

    <!-- Recent Leave Requests Table -->
    <h3 style="margin-top:40px; margin-bottom:20px; color:#2c3e50;">Recent Leave Requests</h3>
    <table class="recent-table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Days</th>
                <th>Status</th>
                <th>Requested At</th>
            </tr>
        </thead>
      `<tbody>
@foreach ($recentLeaves ?? [] as $leave)
<tr>
    <td>{{ $leave->user->first_name ?? 'Unknown' }}</td>
    <td>{{ $leave->leave_type }}</td>
    <td>{{ date('d M Y', strtotime($leave->start_date)) }} to {{ date('d M Y', strtotime($leave->end_date)) }}</td>
    @php
        $days = (strtotime($leave->end_date) - strtotime($leave->start_date)) / 86400 + 1;
    @endphp
    <td>{{ $days }}</td>
    <td>
        @php
            $statusClass = '';
            if($leave->status == 'Pending') $statusClass='status-pending';
            elseif($leave->status == 'Rejected') $statusClass='status-rejected';
            elseif($leave->status == 'Approved') $statusClass='status-approved';
        @endphp
        <div class="status-label {{ $statusClass }}">{{ $leave->status }}</div>
    </td>
    <td>{{ date('d M Y H:i', strtotime($leave->created_at)) }}</td>
</tr>
@endforeach
@if(empty($recentLeaves))
<tr><td colspan="6" style="text-align:center;">No recent requests</td></tr>
@endif
</tbody>
`

    </table>

</div>

<!-- FontAwesome and Chart.js -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Example Chart for Designation, add here if needed -->
<script>
    // No chart needed here unless you want to display a pie or bar chart for overall stats
</script>
@endsection
