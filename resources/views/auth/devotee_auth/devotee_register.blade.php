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
        min-height: 100vh;
        margin: 0;
        padding: 20px 0;
    }

    .registration_card {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 800px;
        border: 2px solid var(--primary);
        margin: auto;
    }

    .registration_card h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 20px;
        font-size: 24px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        margin-bottom: 15px;
    }

    .form-group.full-width {
        width: 100%;
    }

    .form-group label {
        display: block;
        color: #555;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 5px rgba(212, 160, 23, 0.3);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 60px;
    }

    .btn-register {
        width: 100%;
        padding: 12px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .btn-register:hover {
        background-color: #b78f13;
    }

    .back-links {
        text-align: center;
        margin-top: 20px;
    }

    .back-home {
        display: inline-block;
        margin: 5px 10px;
        text-decoration: none;
        font-size: 14px;
        color: #2c3e50;
        background-color: #dce3ed;
        padding: 8px 12px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .back-home:hover {
        background-color: #c5d2e3;
    }

    .alert {
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .text-danger {
        color: var(--accent);
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .required {
        color: var(--accent);
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
        
        .registration_card {
            margin: 20px;
            padding: 20px 25px;
        }
        
        .back-home {
            display: block;
            margin: 5px 0;
        }
    }
</style>

<div class="registration_card">
    <h2>Devotee Registration</h2>
    
    <div class="back-links">
        <a href="{{ route('dashboard') }}" class="back-home">← Back to Home</a>
    </div>

    <form method="POST" action="{{ route('devotee.register.submit') }}">
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

        <div class="form-row">
            <div class="form-group">
                <label for="username">User Name <span class="required">*</span></label>
                <input type="text" id="username" name="username" placeholder="Enter username" value="{{ old('username') }}" required>
                @error('username')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number <span class="required">*</span></label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="alternate_phone_number">Alternate Phone Number</label>
                <input type="tel" id="alternate_phone_number" name="alternate_phone_number" placeholder="Enter alternate phone" value="{{ old('alternate_phone_number') }}">
                @error('alternate_phone_number')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="gotram">Gotram <span class="required">*</span></label>
                <input type="text" id="gotram" name="gotram" placeholder="Enter gotram" value="{{ old('gotram') }}" required>
                @error('gotram')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group full-width">
            <label for="family_details">Family Details</label>
            <textarea id="family_details" name="family_details" placeholder="Enter family details" rows="3">{{ old('family_details') }}</textarea>
            @error('family_details')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                @error('date_of_birth')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="anniversary">Anniversary</label>
                <input type="date" id="anniversary" name="anniversary" value="{{ old('anniversary') }}">
                @error('anniversary')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group full-width">
            <label for="email">Email ID</label>
            <input type="email" id="email" name="email" placeholder="Enter email address" value="{{ old('email') }}">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group full-width">
            <label for="address">Area / Address</label>
            <textarea id="address" name="address" placeholder="Enter your address" rows="3">{{ old('address') }}</textarea>
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                @error('password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn-register">Register</button>
    </form>

    <div class="back-links">
        <a href="{{ route('devotee.login') }}" class="back-home">← Already a user? Login here</a>
        <a href="{{ route('home') }}" class="back-home">← Back to Home</a>
    </div>
</div>

@endsection