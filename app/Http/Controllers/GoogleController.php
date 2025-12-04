<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Devotee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirects the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handles the callback from Google authentication.
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user information from Google
            $socialiteUser = Socialite::driver('google')->user();

            // 1. PRIMARY CHECK: Find user by EMAIL
            $findUser = Devotee::where('email_id', $socialiteUser->email)->first();

            if ($findUser) {
                // --- CASE A: EXISTING USER (Login) ---
                
                // Update the Google ID if it wasn't there before (Linking account)
                if (empty($findUser->google_id)) {
                    $findUser->update([
                        'google_id' => $socialiteUser->id,
                    ]);
                }
                
                // Log the existing user in
                Auth::guard('devotee')->login($findUser);
                return redirect()->route('devotee.dashboard');
            } else {
                // --- CASE B: NEW USER (Registration) ---
                
                // Generate a unique username from Name/Email prefix
                $baseUsername = Str::slug($socialiteUser->name ?? explode('@', $socialiteUser->email)[0]);
                $username = $baseUsername;

                // Ensure username uniqueness in DB by appending a number if needed
                $count = 1;
                while (Devotee::where('user_name', $username)->exists()) {
                    $username = $baseUsername . $count++;
                }

                // Create a new record
                $user = Devotee::create([
                    'name' => $socialiteUser->name,
                    'email_id' => $socialiteUser->email,
                    'google_id' => $socialiteUser->id,
                    'user_name' => $username, 
                    'password' => Hash::make(Str::random(60)),
                ]);

                // Log the new user in
                Auth::guard('devotee')->login($user);
                return redirect()->route('devotee.dashboard');
            }

        } catch (Exception $e) {
            // Always return to the login page on failure
            dd($e->getMessage()); // Keep this commented out unless actively debugging a new error
            //return redirect()->route('devotee.login')->with('error', 'Google login failed. Please try again.');
        }
    }
}