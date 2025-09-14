<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Devotee;
use Illuminate\Support\Facades\Auth;
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

            // Find the user in the database by their Google ID
            $findUser = Devotee::where('google_id', $socialiteUser->id)->first();

            if ($findUser) {
                // If user exists, log them in
                Auth::guard('devotee')->login($findUser);
                return redirect()->route('devotee.dashboard');
            } else {
                // If user doesn't exist, create a new record
                $user = Devotee::create([
                    'name' => $socialiteUser->name,
                    'email' => $socialiteUser->email,
                    'google_id' => $socialiteUser->id,
                    'password' => bcrypt(rand(100000, 999999)),
                ]);
                
                // Log the new user in
                Auth::guard('devotee')->login($user);
                return redirect()->route('devotee.dashboard');
            }

        } catch (Exception $e) {
            // Handle any errors that occur during the process
            return redirect()->route('devotee.login')->with('error', 'Google login failed. Please try again.');
        }
    }
}