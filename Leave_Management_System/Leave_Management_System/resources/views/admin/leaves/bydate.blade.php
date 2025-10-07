@extends('layouts.admin')

@section('content')
<h2>People on Leave for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h2>
@if($leaves->isEmpty())
    <p>No people on leave for this date.</p>
@else
    <ul>
    @foreach($leaves as $leave)
        <li>
            {{ $leave->user->first_name }} {{ $leave->user->last_name }} ({{ $leave->leave_type }})
        </li>
    @endforeach
    </ul>
@endif
<a href="{{ route('admin.calendar.index') }}">Back to Calendar</a>
@endsection
