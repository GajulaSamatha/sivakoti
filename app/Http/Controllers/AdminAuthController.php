<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Devotee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class AdminController extends Controller{
    public function login(){
        return view('admin_login');
    }
}