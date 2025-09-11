<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class superadmin_DashboardController extends Controller
{
    /**
     * Show the superadmin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('superadmin.superadmin_dashboard');
    }
}
