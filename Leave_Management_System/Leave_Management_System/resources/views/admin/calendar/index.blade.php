@extends('layouts.admin')

@section('content')
<style>
    body {
        font-family: "Century Gothic", sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    h1 {
        font-weight: 700;
        font-size: 40px;
        color: #2c3e50;
        margin: 0 0 24px 0;
        border-left: 6px solid #138056ff;
        padding-left: 15px;
        letter-spacing: 0.04em;
    }
      h5{
        font-weight: 700;
        font-size: 20px;
        color: #5c0646ff;
        margin: 0 0 24px 0;
       margin-top:20px;
        padding-left: 15px;
        letter-spacing: 0.04em;
    }
    .page-header {
        margin-bottom: 20px;
    }
    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-top: 20px;
    }
    .day {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        height: 100px;
        background: #fff;
        cursor: pointer;
        transition: background 0.2s ease-in-out;
    }
    .day:hover {
        background-color: #f1f3f5;
    }
    .day-header {
        font-weight: bold;
        text-align: center;
        background-color: #f8f9fa;
        padding: 8px;
        border-radius: 4px;
    }

    /* Leave color codes */
    .leave-Annual { background-color: #368585ff !important; }          /* Blue */
    .leave-Sick { background-color: #7c3c69ff !important; }            /* Red */
    .leave-Maternity { background-color: #91484dff !important; }       /* Soft Pink */
    .leave-Paternity { background-color: #65911fff !important; }       /* Light Green */
    .leave-Bereavement { background-color: #8363bdff !important; }     /* Purple */
    .leave-Unpaid { background-color: #ecbf2aff !important; }          /* Yellow */
    .leave-Compassionate { background-color: #2edf0aff !important; }   /* Teal */

    .legend span {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 3px;
        margin-right: 5px;
        margin-top:30px;
    }
</style>

<div class="container mt-4">
    <div class="page-header">
        <h1><strong>Team Leave Calendar</strong></h1>
        
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" class="btn btn-light">&lt;</a>
        <h5 class="mb-0">{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</h5>
        <a href="{{ route('admin.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" class="btn btn-light">&gt;</a>
    </div>

    <div class="legend mb-3">
        <span style="background:#8363bdff;"></span> Annual
        <span style="background:#368585ff; margin-left:10px;"></span> Sick
        <span style="background:#7c3c69ff; margin-left:10px;"></span> Maternity
        <span style="background:#91484dff; margin-left:10px;"></span> Paternity
        <span style="background:65911fff; margin-left:10px;"></span> Bereavement
        <span style="background:#ecbf2aff; margin-left:10px;"></span> Unpaid
        <span style="background:#2edf0aff; margin-left:10px;"></span> Compassionate
    </div>

    <div class="calendar">
        @php
            $firstDay = \Carbon\Carbon::create($year, $month, 1);
            $startDay = $firstDay->dayOfWeek;
            $daysInMonth = $firstDay->daysInMonth;
        @endphp

        {{-- Day headers --}}
        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
            <div class="day-header">{{ $day }}</div>
        @endforeach

        {{-- Blank boxes before the first day --}}
        @for($i=0; $i<$startDay; $i++)
            <div></div>
        @endfor

        {{-- Calendar days --}}
        @for($day=1; $day <= $daysInMonth; $day++)
            @php
                $date = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                $leaveType = isset($events[$date]) ? $events[$date][0]['type'] : null;
            @endphp
            <div class="day {{ $leaveType ? 'leave-' . $leaveType : '' }}" onclick="showDetails('{{ $date }}')">
                <strong>{{ $day }}</strong>
                @if(isset($events[$date]))
                    <small class="d-block mt-2 text-muted">{{ count($events[$date]) }} on leave</small>
                @endif
            </div>
        @endfor
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">People on Leave</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="leaveDetails">
        <p>Select a date to view leave details.</p>
      </div>
    </div>
  </div>
</div>

<script>
function showDetails(date) {
    const events = @json($events);
    let details = '';

    if (events[date]) {
        details += `<ul>`;
        events[date].forEach(e => {
            details += `<li><strong>${e.user}</strong> - ${e.type} Leave</li>`;
        });
        details += `</ul>`;
    } else {
        details = 'No leaves on this day.';
    }

    document.getElementById('leaveDetails').innerHTML = details;
    const modal = new bootstrap.Modal(document.getElementById('leaveModal'));
    modal.show();
}
</script>
@endsection
