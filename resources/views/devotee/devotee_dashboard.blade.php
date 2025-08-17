@extends('layouts.devotee_layouts.devotee_menu_sidebar')  

@section('content')
  <style>
    :root {
      --primary: #d4a017;
      --accent: #8b0000;
      --background: #fff8e7;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: var(--background);
      margin: 0;
      padding: 0;
      height:100vh;
    }

    h1 {
      text-align: center;
      color: var(--accent);
      padding: 20px 0;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 2fr));
      gap: 20px;
      padding: 20px;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background-color: #fff;
      border: 2px solid var(--primary);
      border-radius: 15px;
      padding: 25px;
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>

  <h1>Welcome, Devotee</h1>

  <div class="dashboard">
    <a href="upcoming-events.html" class="card">
      <i class="fas fa-calendar-plus"></i>
      <h3>Upcoming Events</h3>
    </a>
    <a href="{{route('devotee.bookings')}}" class="card">
      <i class="fas fa-list"></i>
      <h3>My Bookings</h3>
    </a>
    <a href="notifications.html" class="card">
      <i class="fas fa-bell"></i>
      <h3>Notifications</h3>
    </a>
    <a href="{{route('devotee.profile')}}" class="card">
      <i class="fas fa-user-circle"></i>
      <h3>My Profile</h3>
    </a>
    <a href="{{route('devotee.donations')}}" class="card">
      <i class="fas fa-hand-holding-heart"></i>
      <h3>Donations</h3>
    </a>
    <a href="{{route('devotee.logout')}}" class="card">
      <i class="fas fa-sign-out-alt"></i>
      <h3>Logout</h3>
    </a>
  </div>

@endsection