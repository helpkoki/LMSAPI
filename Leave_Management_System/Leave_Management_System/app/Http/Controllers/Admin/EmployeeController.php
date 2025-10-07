<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // Show list of employees
    public function index()
    {
        $employees = User::whereIn('role', ['employee', 'manager','admin'])->get();
        return view('admin.employees.index', compact('employees'));
    }

    // Show create employee form
    public function create()
    {
        return view('admin.employees.create');
    }

    // Store new employee
   public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:employee,manager',
    ]);

    User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'department' => $request->department,
        'designation' => $request->designation,
        'email' => $request->email,
       'password' => Hash::make($request->password),// will be hashed by model mutator
        'role' => $request->role,
    ]);

    return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
}
    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
