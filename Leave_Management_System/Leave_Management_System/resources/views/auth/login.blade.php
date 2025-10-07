<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Leave Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            padding: 40px;
            margin: 0;
        }
        .login-container {
            max-width: 800px;
            margin: auto;
            margin-top: 70px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px #bdb6b6ff;
            text-align: center;
        }
        .login-logo {
            margin-bottom: 30px;
        }
        .login-logo img {
            height: 80px;
            width: auto;
        }
        h2 {
            margin-bottom: 25px;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #a7ff43ff;
            color: #050505ff;
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
        label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            font-size: 14px;
            border-color: #a7ff43ff;
            color: #4a4a4a;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            text-align: left;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 3px;
            text-align: left;
        }
        button {
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
            display: flex;
            align-items: center;
            margin: 30px auto 0;
            justify-content: center;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #040505ff;
        }
        .branding {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85em;
            color: #888;
        }
        .remember-me {
            margin-top: 15px;
            text-align: left;
        }
        .extra-links {
            margin-top: 20px;
            text-align: center;
        }
        .extra-links a {
            color: #2c440bff;
            font-weight: 600;
            text-decoration: none;
            margin: 0 5px;
            transition: text-decoration 0.3s ease;
        }
        .extra-links a:hover {
            text-decoration: underline;
        }
        .user-type-select {
            margin: 20px 0 10px;
            display: flex;
            justify-content: center;
            gap: 25px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .user-type-select label {
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            color: #444;
        }
        .user-type-select input[type="radio"] {
            margin-right: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Moepi Publishing Logo" />
        </div>

        <h2>Login</h2>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="user-type-select" role="radiogroup" aria-labelledby="userTypeLabel">
                <label><input type="radio" name="user_type" value="employee" checked> Employee</label>
                <label><input type="radio" name="user_type" value="manager"> Manager</label>
                <label><input type="radio" name="user_type" value="admin"> Admin</label>
            </div>

            <label id="emailOrNumberLabel" for="email_or_number">Email</label>
            <input type="text" name="email_or_number" id="email_or_number" value="{{ old('email_or_number') }}" required autofocus />
            @error('email_or_number')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            

            <button type="submit">Login</button>
        </form>

      

        <div class="branding">
            Copyright 2022 Moepi Publishing. All Rights Reserved.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[name="user_type"]');
            const label = document.getElementById('emailOrNumberLabel');

            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'admin') {
                        label.textContent = 'Admin Number';
                    } else {
                        label.textContent = 'Email';
                    }
                });
            });
        });
    </script>
</body>
</html>
