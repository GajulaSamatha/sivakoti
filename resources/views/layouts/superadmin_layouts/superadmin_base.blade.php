<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard - Sivakoti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #d4a017;
            --accent: #8b0000;
            --background: #f7f7f7;
            --sidebar-bg: #2c3e50;
            --link-color: #ecf0f1;
            --link-hover-bg: #34495e;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: var(--background);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary);
            padding: 20px 0;
            color: var(--link-color);
            position: fixed;
            height: 100%;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: var(--link-color);
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar-menu li a:hover {
            background-color: white;
            color:black;
        }

        .sidebar-menu i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
        }

        .header {
            text-align: center;
            color: var(--accent);
            padding-bottom: 20px;
            border-bottom: 2px solid var(--primary);
            margin-bottom: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border: 2px solid var(--primary);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            background-color: var(--primary);
            color: #fff;
        }

        .card i {
            font-size: 36px;
            margin-bottom: 10px;
            color: var(--accent);
        }

        .card:hover i {
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Sivakoti Admin</h2>
        <ul class="sidebar-menu">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('superadmin.categories.index') }}"><i class="fas fa-sitemap"></i> Manage Categories</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Events & Pujas</a></li>
            <li><a href="#"><i class="fas fa-file-alt"></i> Pages</a></li>
            <li><a href="#"><i class="fas fa-newspaper"></i> Posts</a></li>
            <li><a href="#"><i class="fas fa-images"></i> Galleries</a></li>
            <li><a href="#"><i class="fas fa-external-link-alt"></i> Popups</a></li>
            <li><a href="#"><i class="fas fa-bars"></i> Menus</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="#"><i class="fas fa-user-cog"></i> Admins</a></li>
            <li><a href="#"><i class="fas fa-cogs"></i> Settings</a></li>
            <li><a href="{{ route('devotee.logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>@yield('page_title')</h1>
        </div>
        @yield('content')
    </div>

</body>
</html>