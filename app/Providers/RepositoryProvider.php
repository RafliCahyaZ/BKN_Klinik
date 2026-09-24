<?php

namespace App\Providers;

use App\Repository\AppointmentRepository;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\DiagnosisRepository;
use App\Repository\DiagnosisRepositoryEloquent;
use App\Repository\LoginLogRepository;
use App\Repository\LoginLogRepositoryEloquent;
use App\Repository\MedicineRepository;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepository;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\PatientRepository;
use App\Repository\PatientRepositoryEloquent;
use App\Repository\RegistrationRepository;
use App\Repository\RegistrationRepositoryEloquent;
use App\Repository\TreatmentRepository;
use App\Repository\TreatmentRepositoryEloquent;
use App\Repository\UnitRepository;
use App\Repository\UnitRepositoryEloquent;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    public $singletons = [
        PatientRepository::class => PatientRepositoryEloquent::class,
        DiagnosisRepository::class => DiagnosisRepositoryEloquent::class,
        TreatmentRepository::class => TreatmentRepositoryEloquent::class,
        UserRepository::class => UserRepositoryEloquent::class,
        RegistrationRepository::class => RegistrationRepositoryEloquent::class,
        AppointmentRepository::class => AppointmentRepositoryEloquent::class,
        MedicineRepository::class => MedicineRepositoryEloquent::class,
        MedicineStockTransactionRepository::class => MedicineStockTransactionRepositoryEloquent::class,
        UnitRepository::class => UnitRepositoryEloquent::class,
        LoginLogRepository::class => LoginLogRepositoryEloquent::class,
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
