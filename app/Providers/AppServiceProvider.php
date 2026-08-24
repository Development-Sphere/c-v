<?php

namespace App\Providers;

use App\Listeners\AttachGuestCvsToUser;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
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
        Event::listen(Registered::class, [AttachGuestCvsToUser::class, 'handleRegistered']);
        Event::listen(Login::class, [AttachGuestCvsToUser::class, 'handleLogin']);
    }
}
