<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\DevoteeAuthController;
use App\Http\Controllers\Superadmin\CategoryController;
use App\Http\Controllers\Superadmin\superadmin_DashboardController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\Superadmin\EventPoojaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Auth and Settings routes for the default user guard
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Devotee Authentication Routes
Route::get('/devotee/login', [DevoteeAuthController::class, 'showLoginForm'])->name('devotee.login');
Route::post('/devotee/login', [DevoteeAuthController::class, 'login'])->name('devotee.login.submit');
Route::get('/devotee/register', [DevoteeAuthController::class, 'showRegisterForm'])->name('devotee.register');
Route::post('/devotee/register', [DevoteeAuthController::class, 'register'])->name('devotee.register.submit');
Route::get('/devotee/devotee_logout', [DevoteeAuthController::class, 'devotee_logout'])->name('devotee.logout');

// Google Login Routes
Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.login.callback');

// Devotee Authenticated Routes
Route::middleware(['auth:devotee'])->group(function () {
    Route::get('/devotee/devotee_dashboard', [DevoteeAuthController::class, 'dashboard'])->name('devotee.dashboard');
    Route::get('/devotee/devotee_profile', [DevoteeAuthController::class, 'devotee_profile'])->name('devotee.profile');
    Route::get('/devotee/devotee_bookings', [DevoteeAuthController::class, 'devotee_bookings'])->name('devotee.bookings');
    Route::get('/devotee/devotee_donations', [DevoteeAuthController::class, 'devotee_donations'])->name('devotee.donations');
});

// Superadmin Routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [superadmin_DashboardController::class, 'index'])->name('superadmin.dashboard');

    // Category routes for Superadmin
    Route::get('/superadmin/categories', [CategoryController::class, 'index'])->name('superadmin.categories.index');
    Route::get('/superadmin/categories/create', [CategoryController::class, 'create'])->name('superadmin.categories.create');
    Route::post('/superadmin/categories', [CategoryController::class, 'store'])->name('superadmin.categories.store');
    Route::get('/superadmin/categories/{category}', [CategoryController::class, 'show'])->name('superadmin.categories.view');
    Route::get('/superadmin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('superadmin.categories.edit');
    Route::put('/superadmin/categories/{category}', [CategoryController::class, 'update'])->name('superadmin.categories.update');
    Route::delete('/superadmin/categories/{category}', [CategoryController::class, 'destroy'])->name('superadmin.categories.destroy');
    Route::post('/superadmin/categories/{category}/toggle-enabled', [CategoryController::class, 'toggleEnabled'])->name('superadmin.categories.toggle-enabled');
    Route::resource('events-poojas', EventPoojaController::class)->names([
        'index' => 'superadmin.events_poojas.index',
    'create' => 'superadmin.events_poojas.create',
    'store' => 'superadmin.events_poojas.store',
    'show' => 'superadmin.events_poojas.show',
    'edit' => 'superadmin.events_poojas.edit',
    'update' => 'superadmin.events_poojas.update',
    'destroy' => 'superadmin.events_poojas.destroy',
    ]);
});

require __DIR__.'/auth.php';