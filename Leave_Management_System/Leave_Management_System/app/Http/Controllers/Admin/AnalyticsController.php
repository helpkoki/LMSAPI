<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $monthFilter = $request->query('month');

        $leaveQuery = LeaveRequest::query();

        if ($monthFilter) {
            $now = Carbon::now();

            if ($monthFilter === 'this_month') {
                $leaveQuery->whereYear('start_date', $now->year)
                           ->whereMonth('start_date', $now->month);
            } elseif ($monthFilter === 'this_quarter') {
                $quarter = ceil($now->month / 3);
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;

                $leaveQuery->whereYear('start_date', $now->year)
                           ->whereBetween(DB::raw('MONTH(start_date)'), [$startMonth, $endMonth]);
            } elseif ($monthFilter === 'this_year') {
                $leaveQuery->whereYear('start_date', $now->year);
            } else {
                // Specific month filter YYYY-MM
                $leaveQuery->whereYear('start_date', substr($monthFilter, 0, 4))
                           ->whereMonth('start_date', substr($monthFilter, 5, 2));
            }
        }

        $totalLeaves = $leaveQuery->count();

        $pendingRequests = (clone $leaveQuery)->where('status', 'Pending')->count();

        $activeEmployees = User::where('role', 'employee')->where('is_active', 1)->count();
        $totalEmployees = User::where('role', 'employee')->count();

        $approvedCount = (clone $leaveQuery)->where('status', 'Approved')->count();
        $approvalRate = $totalLeaves > 0 ? round(($approvedCount / $totalLeaves) * 100, 1) : 0;

        $leavesByType = (clone $leaveQuery)
            ->select('leave_type', DB::raw('count(*) as total'))
            ->groupBy('leave_type')
            ->get();

        $leavesByDesignation = (clone $leaveQuery)
            ->join('users', 'leave_requests.user_id', '=', 'users.id')
            ->select('users.designation', DB::raw('count(leave_requests.id) as total'))
            ->groupBy('users.designation')
            ->get();

        $designationLabels = $leavesByDesignation->pluck('designation')->toArray();
        $designationData = $leavesByDesignation->pluck('total')->toArray();

        $topLeaveTakers = (clone $leaveQuery)
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $totalForTop = $topLeaveTakers->sum('total');

        $months = LeaveRequest::selectRaw("DATE_FORMAT(start_date, '%Y-%m') as month")
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        return view('admin.analytics.index', compact(
            'totalLeaves',
            'pendingRequests',
            'activeEmployees',
            'totalEmployees',
            'approvedCount',
            'approvalRate',
            'leavesByType',
            'topLeaveTakers',
            'totalForTop',
            'designationLabels',
            'designationData',
            'months',
            'monthFilter'
        ));
    }
}
