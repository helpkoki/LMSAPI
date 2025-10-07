<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Signup - Leave Management</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            padding: 40px 20px;
            margin: 0;
        }
        .signup-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 70px;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 35px 30px;
            box-shadow: 0 0 10px #bdb6b6ff;
            border: 1px solid #dde3ea;
            text-align: center;
        }
        .signup-logo {
            margin-bottom: 30px;
        }
        .signup-logo img {
            height: 80px;
            width: auto;
        }
        h2 {
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 15px;
            color: #1b1e40;
            letter-spacing: -0.6px;
        }
        label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #4a4a4a;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            text-align: left;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            margin-top: 7px;
            padding: 13px 17px;
            font-size: 18px;
            border-radius: 8px;
            border: 1px solid #9ef755ff;
            color: #787c8d;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #a7ff43ff;
            color: #050505ff;
        }
        .error {
            color: #ff1e5a;
            font-size: 13px;
            margin-top: 5px;
            text-align: left;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        button, .btn-login {
            padding: 15px 40px;
            font-weight: 600;
            font-size: 18px;
            border-radius: 12px;
            cursor: pointer;
            border: none;
            background-color: #b2cd16;
            color: white;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }
        button:hover, .btn-login:hover {
            background-color: #0f140acc;
        }
        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .branding {
            margin-top: 35px;
            font-size: 13px;
            color: #a2a6b1;
            user-select: none;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Moepi Publishing Logo" />
        </div>

        <h2>SignUp</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('signup.post') }}">
            @csrf

            <label for="first_name">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required />
            @error('first_name')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required />
            @error('last_name')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="department">Department</label>
            <input type="text" name="department" value="{{ old('department') }}" required />
            @error('department')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="designation">Designation</label>
            <input type="text" name="designation" value="{{ old('designation') }}" required />
            @error('designation')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="email">Username (Email)</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input type="password" name="password" required />
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" required />

            <div class="button-group">
                <button type="submit">SignUp</button>
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            </div>
        </form>

        <div class="branding">
            Copyright 2022 Moepi Publishing. All Rights Reserved.
        </div>
    </div>
</body>
</html>
