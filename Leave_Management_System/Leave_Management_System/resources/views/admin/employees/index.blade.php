@extends('layouts.admin')

@section('title', 'Employees List')

@section('content')
<style>
    /* Existing styles... */
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        font-family: Arial, sans-serif;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px 16px;
        text-align: left;
    }
    th {
        background-color: #dff8eeff;
        color: black;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .delete-button {
        background-color: #ef4444;
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
    .delete-button:hover {
        background-color: #b91c1c;
    }

    /* New button style */
    .add-employee-button {
    background-color: #b2cd16;
    color: white;
    padding: 6px 12px;      /* reduced from 12px 20px */
    border-radius: 8px;
    text-decoration: none;
    font-weight: 300;
    float: right;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
    font-size: 25px;        /* add smaller font size */
}

    .add-employee-button:hover {
        background-color: #173023ff;
    }
</style>

<h1>Employees List
    <a href="{{ route('admin.employees.create') }}" class="add-employee-button">+ Create Employee</a>
</h1>

<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->first_name }}</td>
                <td>{{ $employee->last_name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->department }}</td>
                <td>{{ $employee->designation }}</td>
                <td>{{ ucfirst($employee->role) }}</td>
                <td>{{ $employee->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
