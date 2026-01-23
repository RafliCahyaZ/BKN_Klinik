<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;


class HistoryController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository
    ) {}

    public function print($id)
    {
        $registration = $this->registrationRepository->getById($id);


        return view("dashboard.patient.history.print", [
            "registration" => $registration,
            "printTitle" => "Sistem Informasi Praktik Dokter - Detail History Pasien",
            "No.Reg" => $registration->id
        ]);


    }



    public function show($id)
    {
        $registration = $this->registrationRepository->getById($id);

        return view("dashboard.patient.history.show", [
            "registration" => $registration
        ]);
    }
}
