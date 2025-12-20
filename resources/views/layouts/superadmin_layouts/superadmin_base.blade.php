<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Sivakoti Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @livewireStyles

    <style>
        :root {
            /* Color Variables */
            --primary: #d4a017;
            --accent: #8b0000;
            --background: #f7f7f7;
            --sidebar-bg: #2c3e50; /* Note: Not currently used, as sidebar background is set to --primary */
            --link-color: #ecf0f1;
            --link-hover-bg: #34495e;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: var(--background);
            /* FIX: Removed display: flex; as it conflicted with position: fixed sidebar. */
            min-height: 100vh;
        }

        /* --- SIDEBAR STYLES --- */
        .sidebar {
            width: 250px;
            background-color: var(--primary);
            padding: 20px 0;
            color: var(--link-color);
            /* Ensures sidebar stays in place */
            position: fixed; 
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: white;
            color: var(--primary);
            font-weight: bold;
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* --- MAIN CONTENT STYLES --- */
        .main-content {
            /* FIX: This margin-left now correctly pushes the content away from the fixed sidebar. */
            margin-left: 250px; 
            flex-grow: 1; /* Retained, though redundant without body display:flex */
            padding: 20px;
        }

        .header {
            text-align: center;
            color: var(--accent);
            padding-bottom: 20px;
            border-bottom: 2px solid var(--primary);
            margin-bottom: 20px;
        }

        /* --- GENERAL STYLES --- */
        .card {
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="sidebar">
        <h2>Sivakoti Admin</h2>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('superadmin.dashboard') }}" 
                   class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('superadmin.categories') }}" 
                   class="{{ request()->routeIs('superadmin.categories') ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i> Manage Categories
                </a>
            </li>
            <li>
                <a href="{{ route('superadmin.events_poojas.index') }}" 
                   class="{{ request()->routeIs('superadmin.events_poojas.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Events & Pujas
                </a>
            </li>
            <li>
                <a href="{{ route('superadmin.contacts.index') }}" 
                   class="{{ request()->routeIs('superadmin.contacts.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Contact Messages
                </a>
            </li>
            <li>
                <a href="{{ route('superadmin.popups.index') }}" 
                   class="{{ request()->routeIs('superadmin.popups.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i> Popups
                </a>
            </li>
            <li>
                <a href="{{ route('superadmin.users.index') }}" 
                   class="{{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>@yield('page_title')</h1>
        </div>
        
        @yield('content')
        {{ $slot ?? '' }}
    </div>

    @livewireScripts

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts') 

</body>
</html>