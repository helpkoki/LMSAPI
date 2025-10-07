@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<style>
    .settings-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 0 12px #ddd;
    }
    h1 {
        font-weight: 700;
        font-size: 24px;
        margin-bottom: 20px;
        color: #32384d;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        border-left: 4px solid #a5d817;
        padding-left: 8px;
        color: #4a4a4a;
    }
    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 18px;
        border: 2px solid #a5d817;
        border-radius: 6px;
        font-size: 15px;
        color: #333;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    select:focus {
        outline: none;
        border-color: #819c0f;
        background-color: #f3f9d2;
    }
    .error-message {
        color: red;
        font-size: 13px;
        margin-top: -14px;
        margin-bottom: 10px;
    }
    button[type="submit"] {
        background-color: #a5d817;
        color: #1b1b17;
        font-weight: 700;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button[type="submit"]:hover {
        background-color: #819c0f;
    }
    .success-message {
        background-color: #e6ffed;
        color: #237d23;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 600;
    }
</style>

<div class="settings-container">
    <h1>Update Profile</h1>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

   <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
    @csrf
    
    <label for="first_name">First Name</label>
        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required />
        @error('first_name')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label for="last_name">Last Name</label>
        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" required />
        @error('last_name')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror


    <label for="password">New Password</label>
    <input id="password" name="password" type="password" placeholder="Leave blank to keep current" style="width: 100%; padding: 10px; margin-bottom: 18px; border: 2px solid #a5d817; border-radius: 6px; font-size: 15px;" />
    @error('password')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="password_confirmation">Confirm Password</label>
    <input id="password_confirmation" name="password_confirmation" type="password" style="width: 100%; padding: 10px; margin-bottom: 18px; border: 2px solid #a5d817; border-radius: 6px; font-size: 15px;" />

    <label for="profile_picture">Profile Picture</label>
    <input id="profile_picture" name="profile_picture" type="file" accept="image/*" style="margin-bottom: 18px;"/>

    @if ($user->profile_picture)
        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; margin-bottom: 18px;" />
    @endif

    <button type="submit">Save Changes</button>
</form>

</div>
@endsection
