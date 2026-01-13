<?php

namespace App\Providers;

use App\Repository\PatientRepository;
use App\Repository\PatientRepositoryImpl;
use App\Repository\PatientRepositoryEloquent;
use App\Repository\DiagnosisRepository;
use App\Repository\DiagnosisRepositoryEloquent;
use App\Repository\DiagnosisRepositoryImpl;
use App\Repository\TreatmentRepository;
use App\Repository\TreatmentRepositoryEloquent;
use App\Repository\TreatmentRepositoryImpl;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{

    public $singletons = [
        PatientRepository::class => PatientRepositoryEloquent::class,
        DiagnosisRepository::class => DiagnosisRepositoryEloquent::class,
        TreatmentRepository::class => TreatmentRepositoryEloquent::class,
        UserRepository::class => UserRepositoryImpl::class,
        RegistrationRepository::class => RegistrationRepositoryImpl::class
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
