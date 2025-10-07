@extends('layouts.app')

@section('title', 'Leaves - ' . ucfirst($status))

@section('content')
<div style="max-width: 1000px; margin: 30px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 12px #ccc;">
    <h1>Leaves - {{ ucfirst($status) }}</h1>

    @if($leaves->isEmpty())
        <p style="color: #888; font-style: italic;">No leaves found with status "{{ $status }}".</p>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f1f1f1;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Type</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Start Date</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">End Date</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $leave->leave_type }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $leave->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
