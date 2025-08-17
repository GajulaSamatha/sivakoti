<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Devotee;
use Illuminate\Support\Facades\Hash;

class DevoteeAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.devotee_auth.devotee_login'); // Your view file
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate inputs
        $request->validate([
            'admin_email'    => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        // Attempt login for devotee guard
        if (Auth::guard('devotee')->attempt([
            'email'    => $request->admin_email,
            'password' => $request->admin_password
        ])) {
            return redirect()->intended(route('devotee.dashboard'))
                             ->with('success', 'Login successful!');
        }
        // If failed
        return back()->withErrors([
        'login' => 'The provided credentials do not match our records.',
    ])->onlyInput('login');
    }
    //show register form
    public function showRegisterForm(){
        return view("auth.devotee_auth.devotee_register");
    }

    /**
     * Handle the registration form submission.
     */
    public function register(Request $request)
    {
        // Step 1: Validate the incoming request data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15|unique:devotees,phone_number',
            'alternate_phone_number' => 'nullable|string|max:15',
            'gotram' => 'required|string|max:255',
            'family_details' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'anniversary' => 'nullable|date',
            'email' => 'required|email|unique:devotees,email',
            'address' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Step 2: Create a new Devotee record in the database
        $devotee = Devotee::create([
            'username' => $validatedData['username'],
            'phone_number' => $validatedData['phone_number'],
            'alternate_phone_number' => $validatedData['alternate_phone_number'],
            'gotram' => $validatedData['gotram'],
            'family_details' => $validatedData['family_details'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'anniversary' => $validatedData['anniversary'],
            'email' => $validatedData['email'],
            'address' => $validatedData['address'],
            'password' => Hash::make($validatedData['password']), // Hash the password for security
        ]);

        // Redirect the user to a success page or the dashboard
        return redirect()->route('devotee.login')->with('success', 'Registration successful! Please log in.');
    }
    //devotee dashboard
    public function dashboard(){
        return view('devotee/devotee_dashboard');
    }

    //devotee profile
    public function devotee_profile(){
        return view('devotee.devotee_profile');
    }
    //devotee bookings
    public function devotee_bookings(){
        return view('devotee/devotee_bookings');
    }
    //devotee donations
    public function devotee_donations(){
        return view('devotee.devotee_donations');
    }
    //devotee logout
    public function devotee_logout(){
        return view('devotee.devotee_logout');
    }
}
