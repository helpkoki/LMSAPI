<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        // Optional filter by status (e.g. ?status=Approved)
        $status = $request->query('status');

        $query = LeaveRequest::with('user') // Load user relationship
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', ucfirst(strtolower($status)));
        }

        // Paginate results (10 per page)
        $requests = $query->paginate(10);

        return view('admin.requests.index', compact('requests', 'status'));
    }
}
