<?php

namespace App\Providers;

use Filament\Notifications\Notification;
use Illuminate\Support\ServiceProvider;

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
        // Notification::defaultSubjectRenderUsing(function ($notification) {
        //     return '[Filament] ' . $notification->subject;
        // });
    }
}
