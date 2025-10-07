@extends('layouts.app')

@section('title', 'Leave Application')

@section('content')
<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        min-height: 100vh;
        background: white;
    }
    
   
    .main-content {
        margin-left: 100px;
         margin-top: 40px;  /* sidebar width + some spacing */
        padding: 40px 50px;
        min-height: 100vh;
          width: 1000px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 0 10px #bdb6b6ff;
        box-sizing: border-box;
    }
    .main-content h1 {
        font-weight: 700;
        font-size: 28px;
        color: #4b4b4b;
        margin-bottom: 8px;
    }
    .main-content h2 {
        font-weight: normal;
        color: #b2b6be;
        font-size: 14px;
        margin-bottom: 30px;
    }
    form.leave-form {
        display: flex;
        flex-wrap: wrap;
        gap: 30px 40px;
        font-size: 14px;
    }
    form.leave-form .input-group {
        flex: 1 1 45%;
        display: flex;
        flex-direction: column;
        min-width: 230px;
    }
    form.leave-form .input-group label {
        margin-bottom: 5px;
        font-weight: 600;
        text-transform: none;
    }
    form.leave-form .input-group select,
    form.leave-form .input-group input[type="text"],
    form.leave-form .input-group input[type="date"],
    form.leave-form .input-group textarea {
        padding: 10px 12px;
        border: 1px solid #d0d2d9;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        font-family: inherit;
        box-sizing: border-box;
    }
    form.leave-form .input-group textarea {
        resize: vertical;
        min-height: 70px;
        max-height: 150px;
    }
    /* Drag and Drop file upload style */
    .file-upload {
        flex: 1 1 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border: 2px dashed #d0d2d9;
        border-radius: 8px;
        color: #888;
        font-size: 14px;
        cursor: pointer;
        position: relative;
        text-align: center;
        user-select: none;
    }
    .file-upload:hover {
        background-color: #f9f9f9;
    }
    .file-upload svg {
        width: 36px;
        height: 36px;
        margin-bottom: 10px;
        fill: #888;
    }
    .file-upload input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
    }
    .leave-form .status {
        background-color: #ffffa6;
        color: #7a7400;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 700;
        width: fit-content;
        margin-top: 25px;
    }
    form.leave-form .button-group {
        flex-basis: 100%;
        margin-top: 40px;
        display: flex;
        gap: 20px;
        justify-content: flex-start;
    }
    form.leave-form .button-group button,
    form.leave-form .button-group input[type="button"] {
        padding: 12px 30px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        user-select: none;
    }
    form.leave-form .button-group .submit-btn {
        background-color: #b2cd16;
        color: #1b1b17;
        box-shadow: 0px 10px 50px #b2cd160f;
        transition: background-color 0.25s;
    }
    form.leave-form .button-group .submit-btn:hover {
        background-color: #d5e02d;
    }
    form.leave-form .button-group .cancel-btn {
         background-color: #0a1347;
        color: #eff106;
        box-shadow: 0px 10px 50px #9f9f9f4f;
        transition: background-color 0.25s;
    }
    form.leave-form .button-group .cancel-btn:hover {
        background-color: #7a7a7a;
        color: white;
    }
</style>


<div class="main-content">
    <h1>Leave Application</h1>
    <h2>Fill in the form to request a leave</h2>

    <form class="leave-form" method="POST" action="{{ route('leave.submit') }}" enctype="multipart/form-data">
        @csrf

       
        <label class="section-title" style="flex-basis:100%;font-size:16px;margin-bottom:5px;">Leave Info</label>
        <div class="input-group">
            <label for="leave_type">Types of Leaves:</label>
            <select id="leave_type" name="leave_type" required>
                <option value="">Select Leave Type</option>
                <option value="Annual">Annual Leave</option>
                <option value="Sick">Sick Leave</option>
                <option value="Maternity">Maternity Leave</option>
                <option value="Paternity">Paternity Leave</option>
                <option value="Bereavement">Bereavement Leave</option>
                <option value="Unpaid">Unpaid Leave</option>
                <option value="Compassionate">Compassionate Leave</option>
            </select>
        </div>

        <div class="input-group" style="flex: 1 1 45%;">
            <label for="start_date">Start Date:</label>
            <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required />
        </div>
        <div class="input-group" style="flex: 1 1 45%;">
            <label for="end_date">End Date:</label>
            <input id="end_date" name="end_date" type="date" value="{{ old('end_date') }}" required />
        </div>

        <div class="input-group file-upload">
            <!-- SVG arrow icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#888" aria-hidden="true">
                <path d="M9 16h6v-2H9v2zm-2-6h10v-2H7v2zm1-6h8v2H8V4zm6 16h-4v-2h4v2zm-2-6l-4 4h3v4h2v-4h3l-4-4z"/>
            </svg>
            <input type="file" name="file_uploads" aria-label="Drag and drop or select a file" />
            <span>Drag and drop or select a file</span>
        </div>

       

        <div class="input-group" style="flex-basis: 100%;">
            <label for="important_comment">Important Comment:</label>
            <textarea id="important_comment" name="important_comment" placeholder="Add any important comments here...">{{ old('important_comment') }}</textarea>
        </div>

        <div class="button-group">
            <button type="button" class="cancel-btn">Cancel</button>
            <button type="submit" class="submit-btn">Submit Application</button>
        </div>
    </form>
</div>
@endsection
