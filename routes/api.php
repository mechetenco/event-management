<?php
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


Route::get('/test-mail', function () {
    Mail::raw('This is a test email from Laravel via Mailtrap.', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Mail');
    });

    return 'Test mail sent!';
});

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    });
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//handle the request and send email
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
//show password reset form
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
//update the password
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// Public routes
Route::apiResource('events', EventController::class)
    ->only(['index', 'show']);

// Protected routes
Route::apiResource('events', EventController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum', 'throttle:api']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('events.attendees', AttendeeController::class)
        ->scoped()
        ->only(['store', 'destroy']);
});

Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()
    ->only(['index', 'show']);
