<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\LeaveRequest;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ✅ Only count APPROVED leave requests per type for doughnut chart
        $leaveTypeCounts = LeaveRequest::select('leave_type', DB::raw('count(*) as total'))
            ->where('status', 'Approved')
            ->groupBy('leave_type')
            ->pluck('total', 'leave_type')
            ->toArray();

        // ✅ Pending requests count (still needed separately)
        $pendingRequestsCount = LeaveRequest::where('status', 'Pending')->count();

        // ✅ On Leave Today — approved leaves that include today's date
        $onLeaveTodayCount = LeaveRequest::where('status', 'Approved')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->count();

        // ✅ Active employees
        $activeEmployeesCount = User::where('role', 'employee')
            ->where('is_active', 1)
            ->count();

        // ✅ This Month — count of approved leave requests created this month
        $thisMonthCount = LeaveRequest::where('status', 'Approved')
            ->whereMonth('created_at', now()->month)
            ->count();

        // ✅ Monthly bar chart data (approved requests only)
        $barLabels = [];
        $barData = [];
        for ($i = 1; $i <= 12; $i++) {
            $barLabels[] = date('F', mktime(0, 0, 0, $i, 10));
            $barData[] = LeaveRequest::where('status', 'Approved')
                ->whereMonth('created_at', $i)
                ->count();
        }

       // At the end of your AdminDashboardController@index method, add:

$statusCounts = LeaveRequest::select('status', DB::raw('count(*) as total'))
    ->groupBy('status')
    ->pluck('total', 'status')
    ->toArray();

// Pass to the view, add in the returned array:

return view('admin.dashboard', [
    'leaveTypeCounts' => $leaveTypeCounts,
    'pendingRequestsCount' => $pendingRequestsCount,
    'onLeaveTodayCount' => $onLeaveTodayCount,
    'activeEmployeesCount' => $activeEmployeesCount,
    'thisMonthCount' => $thisMonthCount,
    'barLabels' => $barLabels,
    'barData' => $barData,
    'statusCounts' => $statusCounts,  // new
]);

        
    }
    
}
