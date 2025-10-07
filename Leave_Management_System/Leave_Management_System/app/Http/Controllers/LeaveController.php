<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{

   
public function dashboard()
{
    $userId = auth()->id();

    // Fetch counts per status in a single query using 'whereIn' and grouping
    $statusCounts = LeaveRequest::where('user_id', $userId)
        ->select('status', \DB::raw('count(*) as total'))
        ->whereIn('status', ['Pending', 'Approved', 'Rejected', 'Withdrawn'])
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    // Use null coalesce to get count or zero if missing
    $pendingCount = $statusCounts['Pending'] ?? 0;
    $approvedCount = $statusCounts['Approved'] ?? 0;
    $rejectedCount = $statusCounts['Rejected'] ?? 0;
    $withdrawnCount = $statusCounts['Withdrawn'] ?? 0;

    $leaveTypeCounts = LeaveRequest::where('user_id', $userId)
        ->select('leave_type', \DB::raw('count(*) as total'))
        ->groupBy('leave_type')
        ->pluck('total', 'leave_type')
        ->toArray();

    $barLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $barData = [];
    foreach($barLabels as $month) {
        $barData[] = LeaveRequest::where('user_id', $userId)
            ->whereMonth('created_at', date('m', strtotime($month . ' 1')))
            ->count();
    }

    return view('dashboard', compact(
        'pendingCount',
        'approvedCount',
        'rejectedCount',
        'withdrawnCount',
        'leaveTypeCounts',
        'barLabels',
        'barData'
    ));
}

    public function applyForm()
    {
        return view('apply');
    }

    public function submit(Request $request)
{
    $data = $request->validate([
        'leave_type' => 'required|string|max:120',
    'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',
    'important_comment' => 'nullable|string|max:512',
    'file_uploads' => 'nullable|file|max:2048',
    ]);

    $data['user_id'] = Auth::id();
    $data['status'] = 'Pending';

    if ($request->hasFile('file_uploads')) {
        $data['file_uploads'] = $request->file('file_uploads')->store('leave_files', 'public');
    }

    LeaveRequest::create($data);

    return redirect()->route('apply')->with('success', 'Leave application submitted!');
}
public function myRequests()
{
    $userId = Auth::id();
    $leaveRequests = LeaveRequest::where('user_id', $userId)
        ->orderBy('start_date', 'desc')
        ->get()
        ->map(function ($request) {
            $request->days = \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;
            return $request;
        });

    return view('leave.my_requests', compact('leaveRequests'));
}

public function withdraw($id)
{
    $leaveRequest = LeaveRequest::where('id', $id)
                     ->where('user_id', Auth::id())
                     ->where('status', 'Pending')
                     ->firstOrFail();

    $leaveRequest->status = 'Withdrawn';
    $leaveRequest->save();

    return redirect()->route('leave.myRequests')->with('success', 'Leave request withdrawn.');
}


public function statusLeaves($status)
{
    $userId = auth()->id();
    $leaves = LeaveRequest::where('user_id', $userId)
              ->where('status', ucfirst($status))
              ->get();

    return view('leave.leaves_by_status', compact('leaves', 'status'));
}
public function leavesByDate($date)
{
    $leaves = LeaveRequest::whereDate('start_date', '<=', $date)
        ->whereDate('end_date', '>=', $date)
        ->with('user')
        ->get();

    return view('admin.leaves.bydate', compact('leaves', 'date'));
}


}