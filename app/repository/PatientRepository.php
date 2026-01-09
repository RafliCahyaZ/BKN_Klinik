<?php

namespace App\Repository;


interface PatientRepository
{
    /* return harus array of PatientDTO */
    public function getAll(): array;
}
