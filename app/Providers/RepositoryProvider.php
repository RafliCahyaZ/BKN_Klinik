<?php

namespace App\Providers;

use App\Repository\PatientRepository;
use App\Repository\PatientRepositoryImpl;
use App\Repository\DiagnosisRepository;
use App\Repository\DiagnosisRepositoryImpl;
use App\Repository\TreatmentRepository;
use App\Repository\TreatmentRepositoryImpl;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{

    public $singletons = [
        PatientRepository::class => PatientRepositoryImpl::class,
        DiagnosisRepository::class => DiagnosisRepositoryImpl::class,
        TreatmentRepository::class => TreatmentRepositoryImpl::class,
        UserRepository::class => UserRepositoryImpl::class
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
