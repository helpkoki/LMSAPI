<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Leave Management System')</title>

    <style>
        html, body {
            margin: 0; padding: 0; height: 100%;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: white; color: #333;
        }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0;
            height: 100vh; width: 256px;
            background-color: #f8f9fd;
            padding: 30px 20px;
            color: #525e06;
            display: flex; flex-direction: column;
            box-sizing: border-box;
            box-shadow: 4px 0 8px rgba(0,0,0,0.1);
        }
        .sidebar .logo {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
        }
        .sidebar .logo img {
            max-width: 90px;
        }
        .sidebar nav.menu {
            display: flex; flex-direction: column;
            gap: 22px; padding: 0;
        }
        .sidebar nav.menu a {
            color: inherit;
            font-weight: 700;
            font-size: 15px;
            padding: 10px 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .sidebar nav.menu a.active,
        .sidebar nav.menu a:hover {
            background-color: #0a1347;
            color: #eff106;
        }

        /* Navbar */
        nav.navbar {
            position: fixed; /* Fixed to top */
            top: 0;
            left: 0;
            width: 100%; /* Full width */
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            background-color: #fff;
            box-shadow: 0 0 10px #bdb6b6ff;
            border-bottom: 1px solid #e6ebf1;
            z-index: 10000;
            user-select: none;
        }
        .navbar-logo {
            font-weight: 700;
            font-size: 22px;
            color: #4353ff;
            cursor: pointer;
            letter-spacing: 1.2px;
        }

        /* Profile */
        .navbar-profile {
            position: relative;
            display: flex;
            align-items: center;
            margin-right: 40px;
            cursor: pointer;
        }
        .profile-circle {
            width: 42px; height: 42px;
            background-color: #a5d817;
            border-radius: 50%;
            color: white;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            user-select: none;
        }
        .profile-image {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            user-select: none;
        }
        .profile-dropdown {
            position: absolute;
            top: 52px; right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            min-width: 140px;
            display: none;
            flex-direction: column;
            z-index: 1000;
        }
        .profile-dropdown.show {
            display: flex;
        }
        .profile-dropdown a {
            padding: 12px 18px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }
        .profile-dropdown a:last-child {
            border-bottom: none;
        }
        .profile-dropdown a:hover {
            background-color: #f0f2f7;
        }

        /* Main Content */
        main {
            margin-left: 256px;
            margin-top: 70px;
            padding: 40px;
            max-width: 1500px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profile = document.getElementById('profileMenu');
            const dropdown = document.getElementById('profileDropdown');

            if (profile && dropdown) {
                profile.addEventListener('click', () => {
                    dropdown.classList.toggle('show');
                });

                window.addEventListener('click', (e) => {
                    if (!profile.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
</head>
<body>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="navbar-logo" onclick="window.location.href='{{ url('/') }}'">
            <img src="{{ asset('images/logo.png') }}" alt="Moepi Publishing Logo" style="height:70px; width:auto; vertical-align: middle;">
        </div>

        @auth
        @php
            $user = auth()->user();
        @endphp
        <div class="navbar-profile" id="profileMenu" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-image" />
            @else
                <div class="profile-circle" title="{{ $user->first_name }} {{ $user->last_name }}">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                </div>
            @endif

            <div class="profile-dropdown" id="profileDropdown" role="menu" aria-hidden="true">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Logout
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
        @endauth
    </nav>

    <aside class="sidebar" role="navigation" aria-label="Sidebar Menu">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Moepi Publishing Logo" />
        </div>
        <nav class="menu">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('apply') }}">Apply For Leave</a>
            <a href="{{ route('leave.myRequests') }}">My Requests</a>
            <a href="{{ route('policies') }}">Policies</a>
            <a href="{{ route('settings.edit') }}">Settings</a>
        </nav>
    </aside>

    <main>
        @yield('content')
    </main>
</body>
</html>
