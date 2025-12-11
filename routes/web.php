<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DevoteeAuthController;
use App\Http\Controllers\Superadmin\superadmin_DashboardController;
use App\Http\Controllers\Superadmin\EventPoojaController;
use App\Http\Controllers\Superadmin\ContactController;
use App\Http\Controllers\Superadmin\PopupController;
use App\Http\Controllers\Superadmin\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Superadmin\ManageCategories;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// User Settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Devotee Auth Routes
Route::get('/devotee/login', [DevoteeAuthController::class, 'showLoginForm'])->name('devotee.login');
Route::post('/devotee/login', [DevoteeAuthController::class, 'login'])->name('devotee.login.submit');
Route::get('/devotee/register', [DevoteeAuthController::class, 'showRegisterForm'])->name('devotee.register');
Route::post('/devotee/register', [DevoteeAuthController::class, 'register'])->name('devotee.register.submit');
Route::get('/devotee/devotee_logout', [DevoteeAuthController::class, 'devotee_logout'])->name('devotee.logout');

// Google Login
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.login.callback');

// Devotee Protected Routes
Route::middleware(['auth:devotee'])->group(function () {
    Route::get('/devotee/devotee_dashboard', [DevoteeAuthController::class, 'dashboard'])->name('devotee.dashboard');
    Route::get('/devotee/devotee_profile', [DevoteeAuthController::class, 'devotee_profile'])->name('devotee.profile');
    Route::get('/devotee/devotee_bookings', [DevoteeAuthController::class, 'devotee_bookings'])->name('devotee.bookings');
    Route::get('/devotee/devotee_donations', [DevoteeAuthController::class, 'devotee_donations'])->name('devotee.donations');
});

// ===================================================================
// SUPERADMIN ROUTES – CLEAN, CORRECT, ERROR-FREE
// ===================================================================
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [superadmin_DashboardController::class, 'index'])->name('dashboard');

        // Categories – Drag & Drop (Livewire)
        
        Route::get('/categories', ManageCategories::class)->name('categories');
      

        // Events & Poojas
        Route::resource('events-poojas', EventPoojaController::class)->names([
            'index'   => 'events_poojas.index',
            'create'  => 'events_poojas.create',
            'store'   => 'events_poojas.store',
            'show'    => 'events_poojas.show',
            'edit'    => 'events_poojas.edit',
            'update'  => 'events_poojas.update',
            'destroy' => 'events_poojas.destroy',
        ]);

        // Contacts
        Route::resource('contacts', ContactController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('contacts');

        // Popups
        Route::resource('popups', PopupController::class)->names('popups');

        // Users
        Route::resource('users', UserController::class)->names('users');
    });

// Admin Login Placeholder
Route::get('/admin/login', fn() => redirect('/superadmin/dashboard'));

require __DIR__.'/auth.php';