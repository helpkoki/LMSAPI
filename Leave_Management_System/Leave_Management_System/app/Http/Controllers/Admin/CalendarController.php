<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->query('month', Carbon::now()->month);
        $year = (int) $request->query('year', Carbon::now()->year);

        // Start and end of selected month
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // ✅ Fetch approved leaves that overlap the month (same logic as dashboard uses for "on leave today")
        $leaves = LeaveRequest::with('user')
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($sub) use ($startOfMonth, $endOfMonth) {
                        $sub->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                    });
            })
            ->orderBy('start_date')
            ->get();

        // ✅ Build events for each day within the month
        $events = [];
        foreach ($leaves as $leave) {
            $period = Carbon::parse($leave->start_date)
                ->daysUntil(Carbon::parse($leave->end_date)->addDay());

            foreach ($period as $day) {
                if ($day->between($startOfMonth, $endOfMonth)) {
                    $dateKey = $day->format('Y-m-d');
                    $events[$dateKey][] = [
                        'user' => $leave->user->name ?? 'Unknown',
                        'type' => ucfirst($leave->leave_type),
                    ];
                }
            }
        }

        // ✅ Color mapping for each leave type
        $leaveColors = [
            'Annual Leave' => '#10b981', // green
            'Sick Leave' => '#ef4444', // red
            'Family Responsibility' => '#facc15', // yellow
            'Study Leave' => '#3b82f6', // blue
            'Maternity Leave' => '#ec4899', // pink
            'Compassionate Leave' => '#8b5cf6', // purple
            'Unpaid Leave' => '#6b7280', // gray
        ];

        return view('admin.calendar.index', compact('month', 'year', 'events', 'leaveColors'));
    }
}
