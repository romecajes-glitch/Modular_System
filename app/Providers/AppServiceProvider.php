<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    protected function redirectTo()
{
    $user = Auth::user();

    return match ($user->role) {
        'admin' => route('admin.dashboard'),
        'instructor' => route('instructor.dashboard'),
        'student' => route('student.dashboard'),
        'cashier' => route('cashier.dashboard'),
        default => route('login'),
    };
}
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

}
