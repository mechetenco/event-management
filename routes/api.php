<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth:sanctum');

// Events Resource: Protect all routes except index and show
Route::apiResource('events', EventController::class)
    ->except(['index', 'show']) // These remain public
    ->middleware(['auth:sanctum', 'throttle:60,1']); // Authentication required

// Public routes for events (accessible without authentication)
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);




// Attendee Resource: Protect all routes except index and show
Route::apiResource('events', AttendeeController::class)
    ->except(['index', 'show', 'update']) // These remain public
    ->middleware(['auth:sanctum', 'throttle:60,1']); // Authentication required

Route::apiResource('events.attendees', AttendeeController::class)
->scoped()->except(['update']);