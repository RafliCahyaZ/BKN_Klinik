<?php

namespace App\Providers;

use App\Helper\UserRoleHelper;
use App\Service\AuthService;
use App\Service\AuthServiceImpl;
use App\Type\UserRole;
use Illuminate\Support\Facades\Gate;
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
    public function boot(): void
    {
        Gate::define('is-admin', function ($user) {
            return UserRoleHelper::stringToUserRole($user->role) == UserRole::ADMIN;
        });

        Gate::define('is-doctor-or-admin', function ($user) {
            return UserRoleHelper::stringToUserRole($user->role) == UserRole::DOCTOR || UserRoleHelper::stringToUserRole($user->role) == UserRole::ADMIN;
        });

        Gate::define('is-officer-or-admin', function ($user) {
            return UserRoleHelper::stringToUserRole($user->role) == UserRole::OFFICER || UserRoleHelper::stringToUserRole($user->role) == UserRole::ADMIN;
        });

        Gate::define('is-inventory-officer-or-admin', function ($user) {
            return UserRoleHelper::stringToUserRole($user->role) == UserRole::INVENTORY_OFFICER || UserRoleHelper::stringToUserRole($user->role) == UserRole::ADMIN;
        });
        
        Gate::define('is-inventory-officer-or-officer-or-admin', function ($user) {
            return UserRoleHelper::stringToUserRole($user->role) == UserRole::INVENTORY_OFFICER || UserRoleHelper::stringToUserRole($user->role) == UserRole::INVENTORY_OFFICER || UserRoleHelper::stringToUserRole($user->role) == UserRole::ADMIN;
        });
    }
}
