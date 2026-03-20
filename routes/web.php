<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProviderApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page - show only featured platform reviews
Route::get('/', function () {
    $featuredReviews = \App\Models\Review::with(['user', 'provider.user'])
        ->where('is_approved', true)
        ->where('is_featured', true)
        ->where('review_type', 'platform')
        ->latest()
        ->take(6)
        ->get();
    
    $averageRating = \App\Models\Review::where('is_approved', true)->avg('rating') ?? 0;
    $totalReviews = \App\Models\Review::where('is_approved', true)->count();
    
    return view('welcome', compact('featuredReviews', 'averageRating', 'totalReviews'));
});

// Auth routes (register/login/logout)
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Forgot Password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

// Authenticated routes (including email verification)
Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    // Confirm Password
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update Password
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Default dashboard redirect based on role
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('provider')) {
        return redirect()->route('provider.dashboard');
    } else {
        return redirect()->route('client.dashboard');
    }
})->name('dashboard');

// Client dashboard
Route::middleware(['auth', 'role:client'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
    Route::get('/profile', [ClientController::class, 'profile'])->name('client.profile');
    Route::get('/appointments', [ClientController::class, 'appointments'])->name('client.appointments');
    Route::get('/settings', [ClientController::class, 'settings'])->name('client.settings');
    
    Route::get('/appointments/create', [AppointmentController::class, 'clientCreate'])->name('client.appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'clientStore'])->name('client.appointments.store');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('client.appointments.cancel');
});

// Provider dashboard
Route::middleware(['auth', 'role:provider'])->prefix('provider')->group(function () {
    Route::get('/dashboard', [ProviderController::class, 'dashboard'])->name('provider.dashboard');
    Route::get('/profile', [ProviderController::class, 'providerProfile'])->name('provider.profile');
    Route::get('/appointments', [ProviderController::class, 'providerAppointments'])->name('provider.appointments');
    Route::get('/settings', [ProviderController::class, 'providerSettings'])->name('provider.settings');
});

// Admin dashboard
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
});

// User Management Routes (Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Role Management Routes (Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

// Service Management Routes (Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
});

// Provider Management Routes (Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/providers-management', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers-management/create', [ProviderController::class, 'create'])->name('providers.create');
    Route::post('/providers-management', [ProviderController::class, 'store'])->name('providers.store');
    Route::get('/providers-management/{provider}/edit', [ProviderController::class, 'edit'])->name('providers.edit');
    Route::put('/providers-management/{provider}', [ProviderController::class, 'update'])->name('providers.update');
    Route::delete('/providers-management/{provider}', [ProviderController::class, 'destroy'])->name('providers.destroy');
});

// Appointment Management Routes (Admin/Provider)
Route::middleware(['auth', 'role:admin|provider'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('/appointments/{appointment}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
    Route::patch('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

// Client payment routes
Route::middleware('auth')->group(function () {
    // ✅ FIRST — specific routes
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    // ✅ AFTER — wildcard routes  
    Route::get('/payments/{appointment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{appointment}/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
});

// Admin payments (renamed index to adminIndex)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('admin.payments.index');
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
    // Keep existing update if needed for manual status changes
});

// Provider Applications Routes

// Public routes (no auth required)
Route::get('/become-a-provider', [ProviderApplicationController::class, 'create'])->name('provider.application.create');
Route::post('/become-a-provider', [ProviderApplicationController::class, 'store'])->name('provider.application.store');
Route::get('/become-a-provider/success', [ProviderApplicationController::class, 'success'])->name('provider.application.success');

// Admin only - provider applications
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/applications', [ProviderApplicationController::class, 'adminIndex'])->name('admin.applications.index');
    Route::patch('/applications/{application}/approve', [ProviderApplicationController::class, 'approve'])->name('admin.applications.approve');
    Route::patch('/applications/{application}/reject', [ProviderApplicationController::class, 'reject'])->name('admin.applications.reject');
});

// Reviews Routes
// Public - show reviews on welcome page
Route::get('/reviews', [ReviewController::class, 'index']);

// Any logged in user - submit platform reviews
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Client reviews (providers) - for completed appointments
Route::middleware('auth')->group(function () {
    Route::get('/reviews/create/{appointment}', [ReviewController::class, 'create'])->name('reviews.create');
    
    // Provider reviews (clients)
    Route::get('/provider-reviews/create/{appointment}', [ReviewController::class, 'createProviderReview'])->name('provider-reviews.create');
    Route::post('/provider-reviews', [ReviewController::class, 'storeProviderReview'])->name('provider-reviews.store');
});

// Admin only - manage reviews
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'toggleApprove'])->name('admin.reviews.approve');
    Route::patch('/reviews/{review}/featured', [ReviewController::class, 'toggleFeatured'])->name('admin.reviews.featured');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});
