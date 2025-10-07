<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Panel')</title>

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
            background-color: white;
            padding: 30px 20px;
            color: #525e06;
            display: flex; flex-direction: column;
            box-sizing: border-box;
            box-shadow: 4px 0 8px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .sidebar .logo {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar .logo img {
            max-width: 90px;
            height: auto;
        }

        .sidebar nav.menu {
            display: flex; 
            flex-direction: column;
            gap: 22px;
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

        /* Main Content */
        main {
            margin-left: 256px;
            margin-top: 10px;
            padding: 40px;
            max-width: 1300px;
        }
    </style>

    @stack('styles') {{-- Import pushed CSS here --}}
    
    @yield('head')
</head>
<body>
    <aside class="sidebar" role="navigation" aria-label="Sidebar Menu">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Moepi Publishing Logo" style="height:50px; width:auto; vertical-align: middle;">
        </div>

        <nav class="menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.employees') }}" class="{{ request()->routeIs('admin.employees') ? 'active' : '' }}">Employees</a>
            <a href="{{ route('admin.requests') }}" class="{{ request()->routeIs('admin.requests') ? 'active' : '' }}">All Requests</a>
            <a href="{{ route('admin.calendar') }}" class="{{ request()->routeIs('admin.calendar') ? 'active' : '' }}">Calendar</a>
            <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">Analytics</a>
            <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">Settings</a>

            <!-- Logout link styled like others -->
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                @csrf
            </form>
        </nav>
    </aside>

    <main>
        @yield('content')
    </main>

    @stack('scripts') {{-- Import pushed JS here --}}

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
</body>
</html>
