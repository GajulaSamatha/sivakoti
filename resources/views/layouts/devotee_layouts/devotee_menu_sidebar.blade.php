<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devotee Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #FF9933;
            --accent: #DC143C;
            --background: #FFF8DC;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #FF9933 0%, #FF6B35 100%);
            color: #fff;
            padding: 20px;
            height: 100vh;
            transition: transform 0.3s ease-in-out;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .sidebar ul {
            margin-top:50px;
            list-style-type: none;
            padding: 0;
        }

        .sidebar li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar li a:hover {
            background-color: rgba(255, 255, 255, 1);
            transform: translateX(5px);
            color:black;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .main-content {
            flex: 1;
            height: 100vh;
            overflow-y: auto;
        }

        /* Mobile hamburger menu styles */
        .hamburger {
            display: none;
            position: fixed;
            top: 10px;
            left: 20px;
            z-index: 100;
            cursor: pointer;
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateX(-100%);
                z-index: 99;
                width: 250px;
                height: 100vh;
            }

            body.show-menu .sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="{{ route('devotee.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('devotee.profile') }}">My Profile</a></li>
                <li><a href="{{ route('devotee.bookings') }}">My Bookings</a></li>
                <li><a href="{{ route('devotee.donations') }}">Donations</a></li>
                <li><a href="{{ route('devotee.logout') }}">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <div class="hamburger">
        <i class="fas fa-bars"></i>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.body.classList.toggle('show-menu');
        });
    </script>
</body>
</html>