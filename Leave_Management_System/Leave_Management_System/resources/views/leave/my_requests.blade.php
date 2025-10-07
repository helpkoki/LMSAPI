@extends('layouts.app')

@section('title', 'My Leave Requests')

@section('content')
<style>
    .leave-container {
        max-width: 1000px;
        margin: 30px auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 0 16px #ddd;
        padding: 32px 36px;   
    }
    .leave-header-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
    }
    .leave-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #32384d;
    }
    .leave-desc {
        color: #7c8299;
        font-size: 1.05rem;
    }
    .leave-btn {
        background: #b2cd16;
        color: white;
        font-weight: 700;
        font-size: 15px;
        padding: 11px 26px;
        border-radius: 7px;
        border: none;
        transition: background 0.25s;
        text-decoration: none;
        display: inline-block;
    }
    .leave-btn:hover {
        background: #1d1e1fff;
    }
    .leave-table {
        width: 100%;
        border-collapse: collapse;
        background: #f8f9fd;
        border-radius: 10px;
        overflow: hidden;
    }
    .leave-table th, .leave-table td {
        padding: 15px 20px;
        text-align: center;
        font-size: 15px;
    }
    .leave-table th {
        background: #fff;
        color: #727f98;
        font-weight: 700;
        letter-spacing: 1.5px;
    }
    .leave-table tbody tr {
        border-top: 1px solid #eff0f3;
        background: #f8f9fd;
    }
    .leave-type {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        vertical-align: middle;
    }
    .badge-pending {
        background: #fff8d6; color: #927500; border: 1px solid #e3ce7b;
    }
    .badge-approved {
        background: #e8f7dc; color: #237d23; border: 1px solid #b0e49a;
    }
    .badge-rejected {
        background: #fdeaea; color: #ad2828; border: 1px solid #f3bbbb;
    }
    .badge-withdrawn {
        background: #fef3c7; color: #78350f; border: 1px solid #fcd34d;
    }
    .leave-action {
        color: #e22d25;
        background: none;
        border: none;
        font-weight: 700;
        cursor: pointer;
        text-decoration: underline;
        transition: color 0.2s;
        padding: 0;
    }
    .leave-action:hover {
        color: #b0120b;
    }
    .na-action {
        color: #a2a7b3;
        font-style: italic;
    }
    .no-requests {
        text-align: center;
        padding: 30px;
        color: #b2bacd;
        font-style: italic;
    }
</style>

<div class="leave-container">
    <div class="leave-header-flex">
        <div>
            <div class="leave-title">My Leave Requests</div>
            <div class="leave-desc">View and manage your leave applications</div>
        </div>
        <a href="{{ route('apply') }}" class="leave-btn">+ New Request</a>
    </div>

    @if (!$leaveRequests->isEmpty())
    <table class="leave-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Dates</th>
                <th>Days</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($leaveRequests as $request)
            <tr>
                <td class="leave-type">{{ $request->leave_type }}</td>
                <td>{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</td>
                <td>{{ $request->days }}</td>
                <td>
                    @if ($request->status === 'Pending')
                        <span class="badge badge-pending">Pending</span>
                    @elseif ($request->status === 'Approved')
                        <span class="badge badge-approved">Approved</span>
                    @elseif ($request->status === 'Rejected')
                        <span class="badge badge-rejected">Rejected</span>
                    @elseif ($request->status === 'Withdrawn')
                        <span class="badge badge-withdrawn">Withdrawn</span>
                    @endif
                </td>
                <td>
                    @if ($request->status === 'Pending')
                    <form action="{{ route('leave.withdraw', $request->id) }}" method="POST" onsubmit="return confirm('Withdraw this request?');">
                        @csrf
                        @method('PATCH')
                        <button class="leave-action" type="submit">Withdraw</button>
                    </form>
                    @else
                        <span class="na-action">N/A</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <div class="no-requests">You have no leave requests yet.</div>
    @endif
</div>
@endsection
