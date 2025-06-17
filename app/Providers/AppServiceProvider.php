<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Customize the reset link sent via email
    ResetPassword::createUrlUsing(function ($notifiable, string $token) {
        // Update this URL to match your frontend password reset form route
        return url("http://localhost:8001/reset-password-form/{$token}?email={$notifiable->getEmailForPasswordReset()}");
    });

    }
}
