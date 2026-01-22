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
use App\Repository\RegistrationRepository;
use App\Repository\RegistrationRepositoryImpl;
use App\Repository\AppointmentRepository;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\AppointmentRepositoryImpl;
use App\Repository\MedicineRepository;
use App\Repository\MedicineRepositoryImpl;
use App\Repository\MedicineStockTransactionRepository;
use App\Repository\MedicineStockTransactionRepositoryImpl;
use App\Repository\RegistrationRepositoryEloquent;
use App\Repository\UnitRepository;
use App\Repository\UnitRepositoryImpl;
use App\Repository\UserRepositoryEloquent;

class RepositoryProvider extends ServiceProvider
{

    public $singletons = [
        PatientRepository::class => PatientRepositoryEloquent::class,
        DiagnosisRepository::class => DiagnosisRepositoryEloquent::class,
        TreatmentRepository::class => TreatmentRepositoryEloquent::class,
        UserRepository::class => UserRepositoryEloquent::class,
        RegistrationRepository::class => RegistrationRepositoryImpl::class,
        AppointmentRepository::class => AppointmentRepositoryEloquent::class,
        MedicineRepository::class => MedicineRepositoryImpl::class,
        MedicineStockTransactionRepository::class => MedicineStockTransactionRepositoryImpl::class,
        UnitRepository::class => UnitRepositoryImpl::class
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
