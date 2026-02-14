<?php

namespace App\Providers;

use App\Service\AuthService;
use App\Service\AuthServiceImpl;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public $singletons = [
        AuthService::class => AuthServiceImpl::class,
    ];

    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
