<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
 use Carbon\Carbon;


class ReportsController extends Controller
{
  
public function index(Request $request)
{
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    $leaveQuery = LeaveRequest::query();

    if ($startDate && $endDate) {
        $leaveQuery->whereDate('created_at', '>=', $startDate)
                   ->whereDate('created_at', '<=', $endDate);
    }

    $totalRequests = $leaveQuery->count();
    $pendingRequests = (clone $leaveQuery)->where('status', 'Pending')->count();
    $approvedRequests = (clone $leaveQuery)->where('status', 'Approved')->count();
    $rejectedRequests = (clone $leaveQuery)->where('status', 'Rejected')->count();

    // Instead of sum duration_days, calculate sum of days between start_date and end_date for approved
    $daysApproved = (clone $leaveQuery)
        ->where('status', 'Approved')
        ->get()
        ->sum(function ($leave) {
            // Using Carbon diffInDays + 1 for inclusive duration
            return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
        });

    $recentLeaves = (clone $leaveQuery)
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    return view('admin.reports.index', compact(
        'totalRequests', 'pendingRequests', 'approvedRequests', 'rejectedRequests',
        'daysApproved', 'recentLeaves', 'startDate', 'endDate'
    ));
}
}