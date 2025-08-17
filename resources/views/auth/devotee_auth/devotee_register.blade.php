@extends('layouts.app')

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
    }

    .registration-card {
        max-width: 800px;
        background-color: white;
        margin: 50px auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: var(--accent);
    }

    form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-group {
        flex: 1 1 45%;
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
        color: var(--primary);
    }

    input, textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .full-width {
        flex: 1 1 100%;
    }

    button {
        margin-top: 20px;
        padding: 12px 20px;
        background-color: var(--accent);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        width: 100%; /* Make button full width */
    }

    button:hover {
        background-color: #600000;
    }

    .back-home {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: var(--accent);
        font-weight: bold;
    }

    .back-home:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .form-group {
            flex: 1 1 100%;
        }
    }
</style>

<div class="registration-card">
    <h2>Devotee Registration</h2>
    <form action="{{ route('devotee.register.submit') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="username">User Name *</label>
            <input type="text" id="username" name="username" required>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number *</label>
            <input type="tel" id="phone_number" name="phone_number" required>
            @error('phone_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="alternate_phone_number">Alternate Phone Number</label>
            <input type="tel" id="alternate_phone_number" name="alternate_phone_number">
            @error('alternate_phone_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="gotram">Gotram *</label>
            <input type="text" id="gotram" name="gotram" required>
            @error('gotram')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="family_details">Family Details</label>
            <textarea id="family_details" name="family_details" rows="2"></textarea>
            @error('family_details')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth">
            @error('date_of_birth')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="anniversary">Anniversary</label>
            <input type="date" id="anniversary" name="anniversary">
            @error('anniversary')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email ID</label>
            <input type="email" id="email" name="email">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Area / Address</label>
            <textarea id="address" name="address" rows="2"></textarea>
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group full-width">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group full-width">
            <label for="password_confirmation">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group full-width">
            <button type="submit">Register</button>
        </div>
    </form>

    <a class="back-home" href="{{ route('devotee.login') }}">← Already a user? Login here</a><br>
    <a class="back-home" href="{{ route('home') }}">← Back to Home</a>
</div>
@endsection