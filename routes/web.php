<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    // Devotee Login Routes
    Route::get('/devotee/login', [App\Http\Controllers\DevoteeAuthController::class, 'showLoginForm'])->name('devotee.login');
    Route::post('/devotee/login', [App\Http\Controllers\DevoteeAuthController::class, 'login'])->name('devotee.login.submit');
    //devotee register
    Route::get('/devotee/register', [App\Http\Controllers\DevoteeAuthController::class, 'showRegisterForm'])->name('devotee.register');
    Route::post('/devotee/register', [App\Http\Controllers\DevoteeAuthController::class, 'register'])->name('devotee.register.submit');
    //devotee dashboard
    Route::get('/devotee/devotee_dashboard',[App\Http\Controllers\DevoteeAuthController::class, 'dashboard'])->name('devotee.dashboard');
    //devotee profile
    Route::get('/devotee/devotee_profile',[App\Http\Controllers\DevoteeAuthController::class,'devotee_profile'])->name('devotee.profile');
    //devotee bookings
    Route::get('/devotee/devotee_bookings',[App\Http\Controllers\DevoteeAuthController::class,'devotee_bookings'])->name('devotee.bookings');
    //devotee donations
    Route::get('/devotee/devotee_donations',[App\Http\Controllers\DevoteeAuthController::class,'devotee_donations'])->name('devotee.donations');
    //devotee logout
    Route::get('/devotee/devotee_logout',[App\Http\Controllers\DevoteeAuthController::class,'devotee_logout'])->name('devotee.logout');
    
});

// Superadmin routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [App\Http\Controllers\Superadmin\superadmin_DashboardController::class, 'index'])->name('superadmin.dashboard');

    // Category routes
    Route::get('/superadmin/categories', [App\Http\Controllers\Superadmin\CategoryController::class, 'index'])->name('superadmin.categories.index');
    Route::get('/superadmin/categories/create', [App\Http\Controllers\Superadmin\CategoryController::class, 'create'])->name('superadmin.categories.create');

    // The new route for form submission
    Route::post('/superadmin/categories', [App\Http\Controllers\Superadmin\CategoryController::class, 'store'])->name('superadmin.categories.store');
});

// Category routes
Route::get('/superadmin/categories', [App\Http\Controllers\Superadmin\CategoryController::class, 'index'])->name('superadmin.categories.index');
Route::get('/superadmin/categories/create', [App\Http\Controllers\Superadmin\CategoryController::class, 'create'])->name('superadmin.categories.create');
Route::post('/superadmin/categories', [App\Http\Controllers\Superadmin\CategoryController::class, 'store'])->name('superadmin.categories.store');
Route::get('/superadmin/categories/{category}', [App\Http\Controllers\Superadmin\CategoryController::class, 'show'])->name('superadmin.categories.view');

require __DIR__.'/auth.php';
