<style>
    body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background-color: #fff8e7;
  color: #333;
  text-align: center;
}

.header, .footer {
  background-color: #8b0000;
  color: white;
  padding: 1rem;
}
.footer{
    margin-top:100px;
}
.user-selection {
  padding: 2rem;
}

.card-container {
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
  margin-top: 2rem;
}

.card {
  background-color: #fff;
  border: 2px solid #d4a017;
  border-radius: 12px;
  padding: 1.5rem;
  width: 250px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.card h3 {
  color: #8b0000;
}

.btn-primary {
  display: inline-block;
  margin-top: 1rem;
  background-color: #d4a017;
  color: white;
  padding: 10px 20px;
  border: none;
  text-decoration: none;
  border-radius: 6px;
}

  </style>
<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
</head>
<body>

  <!-- Header -->
  <header class="header">
    <h1>🛕 Welcome to Temple Booking Portal</h1>
  </header>

  <!-- Main Section with Cards -->
  <main class="user-selection">
    <h2>Select Your Role</h2>
    <div class="card-container">

      <!-- <div class="card">
        <h3>Super Admin</h3>
        <p>Manage entire portal, admins, poojaris, and reports.</p>
        <a href="superAdminLogin.html" class="btn-primary">Login</a>
      </div> -->

      <div class="card">
        <h3>Admin</h3>
        <p>Manage events, pooja bookings, devotee records.</p>
        <a href="adminLogin.html" class="btn-primary">Login</a>
      </div>

      <div class="card">
        <h3>Devotee</h3>
        <p>Book darshan or special poojas easily.</p>
        <a href="/devotee/login" class="btn-primary">Login</a>
      </div>

    </div>
  </main>


 <!-- Footer -->
  <footer class="footer">
    <p>© 2025 Temple Portal. All rights reserved.</p>
  </footer>
    </div>
</x-layouts.app>
 

</body>
</html>