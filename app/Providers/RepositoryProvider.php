<?php

namespace App\Providers;

use App\Repository\PatientRepository;
use App\Repository\PatientRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{

    public $singletons = [
        PatientRepository::class => PatientRepositoryImpl::class,
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
