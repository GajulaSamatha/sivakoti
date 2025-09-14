@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #d4a017;
        --accent: #8b0000;
        --background: #fff8e7;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--background);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .adminLogin_card {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
        border: 2px solid var(--primary);
        margin:auto;
    }

    .adminLogin_card h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: #555;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btn-login:hover {
        background-color: #b78f13;
    }

    .forgot {
        text-align: right;
        margin-top: 10px;
    }

    .forgot a {
        color: var(--accent);
        text-decoration: none;
        font-size: 14px;
    }
    .back-home {
        display: block;
        text-align: center;
        margin: 20px 0; /* Updated margin for better spacing */
        text-decoration: none;
        font-size: 14px;
        color: #2c3e50;
        background-color: #dce3ed;
        padding: 8px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .back-home:hover {
        background-color: #c5d2e3;
    }
</style>

<div class="adminLogin_card">
    <h2>Devotee Login</h2>
    <a href="{{ route('dashboard') }}" class="back-home">← Back to Home</a>
    <form method="POST" action="{{ route('devotee.login.submit') }}">
        @csrf
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="form-group">
            <label for="admin_email">Username or Email</label>
            <input type="text" id="admin_email" name="admin_email" placeholder="Enter email or username" required>
            @error('admin_email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="password" id="admin_password" name="admin_password" placeholder="Enter password" required>
            @error('admin_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn-login">Login</button>
        <a href="{{ route('google.login') }}">
            <button type="button">Login with Google</button>
        </a>
        <div class="forgot">
          <a href="register" style="float:left">New User?</a>
            <a href="#">Forgot Password?</a>
        </div>
    </form>
    <img src="">
</div>
@endsection