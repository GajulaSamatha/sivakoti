<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Devotee Dashboard</title>
    <style>
    body {
        display: flex;
    }

    .sidebar {
        width: 200px;
        background-color: #34495e;
        color: #fff;
        padding: 20px;
        height: 100vh;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar li a {
        color: #fff;
        text-decoration: none;
        display: block;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .sidebar li a:hover {
        background-color: #2c3e50;
    }

    .main-content {
        flex-grow: 1;
        padding: 20px;
    }
</style>
    </head>
<body>

    <div class="sidebar-menu">
        <aside class="sidebar">
    <nav>
        <ul>
            <li><a href="#">Dashboard</a></li> 
            <li><a href="#">My Profile</a></li>
            <li><a href="#">My Bookings</a></li>
            <li><a href="#">Donations</a></li>
            <li><a href="">Logout</a></li>
        </ul>
    </nav>
</aside>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>