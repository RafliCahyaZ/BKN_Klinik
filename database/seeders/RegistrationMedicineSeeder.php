<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationMedicineSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS registration_medicine (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                registration_id BIGINT UNSIGNED NOT NULL,
                medicine_id BIGINT UNSIGNED NOT NULL,
                quantity INT NOT NULL,
                perhari INT NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,

                PRIMARY KEY (id),

                UNIQUE KEY registration_medicine_registration_id_medicine_id_unique
                    (registration_id, medicine_id),

                KEY registration_medicine_registration_id_foreign
                    (registration_id),

                KEY registration_medicine_medicine_id_foreign
                    (medicine_id),

                CONSTRAINT registration_medicine_registration_id_foreign
                    FOREIGN KEY (registration_id)
                    REFERENCES registrations (id)
                    ON DELETE CASCADE,

                CONSTRAINT registration_medicine_medicine_id_foreign
                    FOREIGN KEY (medicine_id)
                    REFERENCES medicines (id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");
    }
}