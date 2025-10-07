@extends('layouts.admin')

@section('title', 'All Leave Requests')

@section('content')
<style>
    body {
        font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
        background-color: #f8f9fa;
    }
    .page-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        padding: 30px;
        margin: 20px auto;
        max-width: 1300px;
    }
    h1 {
        font-weight: 700;
        font-size: 36px;
        color: #2c3e50;
        margin: 0 0 24px 0;
        border-left: 6px solid #138056ff;
        padding-left: 15px;
        letter-spacing: 0.04em;
    }
    .filter-links a {
        margin-right: 12px;
        text-decoration: none;
        color: #138056ff;
        font-weight: bold;
    }
    .filter-links a.active {
        color: #0a382b;
        text-decoration: underline;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #b9c7c2ff;
        color: black;
        font-weight: bold;
    }
    tr:hover {
        background-color: #f2f2f2;
    }
    .badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 12px;
        font-size: 0.9rem;
        color: white;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-pending { background-color: #facc15; color: black; }
    .badge-approved { background-color: #10b981; }
    .badge-rejected { background-color: #ef4444; }
</style>

<div class="page-container">
    <h1>All Leave Requests</h1>

    <!-- Filters -->
    <div class="filter-links mb-3">
        <a href="{{ route('admin.requests') }}" 
           class="{{ !$status ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.requests', ['status' => 'Pending']) }}" 
           class="{{ $status == 'Pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('admin.requests', ['status' => 'Approved']) }}" 
           class="{{ $status == 'Approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('admin.requests', ['status' => 'Rejected']) }}" 
           class="{{ $status == 'Rejected' ? 'active' : '' }}">Rejected</a>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Important Comment</th>
                <th>File</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $index => $request)
                <tr>
                    <td>{{ $requests->firstItem() + $index }}</td>
                    <td>{{ $request->user->first_name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($request->leave_type) }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($request->status) }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->important_comment ?? '-' }}</td>
                    <td>
                        @if($request->file_uploads)
                            <a href="{{ asset('storage/' . $request->file_uploads) }}" target="_blank">View File</a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:30px;">
                        No leave requests found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $requests->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
