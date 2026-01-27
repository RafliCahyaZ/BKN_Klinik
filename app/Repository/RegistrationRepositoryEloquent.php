<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use App\DTO\UserDTO;
use App\DTO\MedicineDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\TreatmentDTO;
use App\Models\Diagnosis;
use App\Models\Registration;
use App\Models\Treatment;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class RegistrationRepositoryEloquent implements RegistrationRepository
{
    public static function mapRegistrationModelToRegistrationDto($registration): RegistrationDTO
    {
        $formatedRegistration = new RegistrationDTO();
        $formatedRegistration->id = $registration->id;

        $patient = new PatientDTO();
        $patient->id = $registration->patient->id;
        $patient->name = $registration->patient->fullname;
        $patient->age = $registration->patient->getAgeAttribute();
        $patient->telpNumber = $registration->patient->telp_number;
        $patient->address = $registration->patient->address;
        $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
        $patient->gender = $registration->patient->gender;
        $patient->medicalRecordNumber = $registration->patient->medical_record_number;
        $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
        $patient->history = $registration->patient->history;
        $patient->createdAt = $registration->patient->created_at;
        $patient->updatedAt = $registration->patient->updated_at;

        $doctor = new UserDTO();
        if ($registration->doctor) {
            $doctor = UserRepositoryEloquent::mapUserModelToUserDto($registration->doctor);
        }

        $formatedRegistration->patient = $patient;
        $formatedRegistration->doctor = $doctor;
        $formatedRegistration->medicineStockTransactions = []; 
        $formatedRegistration->complaint = $registration->complaint;
        $formatedRegistration->receipt = $registration->receipt;
        $formatedRegistration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
        $formatedRegistration->createdAt = $registration->created_at;
        $formatedRegistration->updatedAt = $registration->updated_at;

        return $formatedRegistration;
    }

    function getAll(): array
    {
        try {
            $registrationsCollections = Registration::with(['patient', 'doctor', 'medicineStockTransactions.inventoryOfficer', 'medicineStockTransactions.medicine.unit'])->get();

            $formatedRegistrations = $registrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;

                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                $patient->name = $registration->patient->fullname;
                $patient->age = $registration->patient->getAgeAttribute();
                $patient->telpNumber = $registration->patient->telp_number;
                $patient->address = $registration->patient->address;
                $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
                $patient->gender = $registration->patient->gender;
                $patient->medicalRecordNumber = $registration->patient->medical_record_number;
                $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
                $patient->history = $registration->patient->history;
                $patient->createdAt = $registration->patient->created_at;
                $patient->updatedAt = $registration->patient->updated_at;

                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor = UserRepositoryEloquent::mapUserModelToUserDto($registration->doctor);
                }

                $medicineStockTransactions = [];
                if ($registration->medicineStockTransactions) {
                    foreach ($registration->medicineStockTransactions as $transaction) {
                        $medicineStockTransactionDTO = new MedicineStockTransactionDTO();
                        $medicineStockTransactionDTO->id = $transaction->id;

                        // Map inventory officer to UserDTO
                        $inventoryOfficer = new UserDTO();
                        if ($transaction->inventoryOfficer) {
                            $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                        }
                        $medicineStockTransactionDTO->inventoryOfficer = $inventoryOfficer;

                        // Map medicine to MedicineDTO
                        $medicine = new MedicineDTO();
                        
                        if ($transaction->medicine) {
                            $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                        }

                        $medicineStockTransactionDTO->medicine = $medicine;
                        $medicineStockTransactionDTO->registration = null; 
                        $medicineStockTransactionDTO->type = $transaction->type;
                        $medicineStockTransactionDTO->quantity = $transaction->quantity;
                        $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
                        $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
                        $medicineStockTransactionDTO->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                        $medicineStockTransactionDTO->notes = $transaction->notes;
                        $medicineStockTransactionDTO->createdAt = $transaction->created_at;
                        $medicineStockTransactionDTO->updatedAt = $transaction->updated_at;
                        $medicineStockTransactions[] = $medicineStockTransactionDTO;
                    }
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor; 
                $formatedRegistration->medicineStockTransactions = $medicineStockTransactions;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;

                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }

    function getById(string $id): ?RegistrationDTO
    {
        try {
            $registration = Registration::with(['doctor', 'medicineStockTransactions.inventoryOfficer', 'medicineStockTransactions.medicine.unit', 'diagnoses', 'treatments'])->find($id);

            if (!$registration) {
                return null;
            }

            $formated_registration = new RegistrationDTO();
            $formated_registration->id = $registration->id;

            $patient = new PatientDTO();
            $patient->id = $registration->patient->id;
            $patient->name = $registration->patient->fullname;
            $patient->age = $registration->patient->getAgeAttribute();
            $patient->telpNumber = $registration->patient->telp_number;
            $patient->address = $registration->patient->address;
            $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
            $patient->gender = $registration->patient->gender;
            $patient->medicalRecordNumber = $registration->patient->medical_record_number;
            $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
            $patient->history = $registration->patient->history;
            $patient->createdAt = $registration->patient->created_at;
            $patient->updatedAt = $registration->patient->updated_at;

            $doctor = new UserDTO();
            if ($registration->doctor) {
                $doctor = UserRepositoryEloquent::mapUserModelToUserDto($registration->doctor);
            }

            $diagnoses = [];

            if ($registration->diagnoses) {
                foreach ($registration->diagnoses as $diagnosis) {
                    $diagnosisDTO = new DiagnosisDTO();
                    $diagnosisDTO->id = $diagnosis->id;
                    $diagnosisDTO->icdCode = $diagnosis->icd_code;
                    $diagnosisDTO->diseaseName = $diagnosis->disease_name;
                    $diagnosisDTO->createdAt = Carbon::parse($diagnosis->created_at);
                    $diagnosisDTO->updatedAt = Carbon::parse($diagnosis->updated_at);
                    $diagnoses[] = $diagnosisDTO;
                }
            }

            // dd($diagnoses);

            $treatments = [];

            if ($registration->treatments) {
                foreach ($registration->treatments as $treatment) {
                    $treatmentDTO = new TreatmentDTO();
                    $treatmentDTO->id = $treatment->id;
                    $treatmentDTO->treatmentCode = $treatment->treatment_code;
                    $treatmentDTO->treatmentName = $treatment->treatment_name;
                    $treatmentDTO->createdAt = Carbon::parse($treatment->created_at);
                    $treatmentDTO->updatedAt = Carbon::parse($treatment->updated_at);
                    $treatments[] = $treatmentDTO;
                }
            }

            $medicineStockTransactions = [];
            if ($registration->medicineStockTransactions) {
                foreach ($registration->medicineStockTransactions as $transaction) {
                    $medicineStockTransactionDTO = new MedicineStockTransactionDTO();
                    $medicineStockTransactionDTO->id = $transaction->id;

                    // Map inventory officer to UserDTO
                    $inventoryOfficer = new UserDTO();
                    if ($transaction->inventoryOfficer) {
                        $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                    }
                    $medicineStockTransactionDTO->inventoryOfficer = $inventoryOfficer;

                    // Map medicine to MedicineDTO
                    $medicine = new MedicineDTO();
                    if ($transaction->medicine) {
                        $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                    }
                    $medicineStockTransactionDTO->medicine = $medicine;

                    // Map registration to RegistrationDTO (without transactions to avoid circular reference)
                    $medicineStockTransactionDTO->registration = null; // Will be set to parent registration
                    $medicineStockTransactionDTO->type = $transaction->type;
                    $medicineStockTransactionDTO->quantity = $transaction->quantity;
                    $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
                    $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
                    $medicineStockTransactionDTO->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                    $medicineStockTransactionDTO->notes = $transaction->notes;
                    $medicineStockTransactionDTO->createdAt = Carbon::parse($transaction->created_at);
                    $medicineStockTransactionDTO->updatedAt = Carbon::parse($transaction->updated_at);
                    $medicineStockTransactions[] = $medicineStockTransactionDTO;
                }
            }

            $formated_registration->patient = $patient;
            $formated_registration->doctor = $doctor;
            $formated_registration->medicineStockTransactions = $medicineStockTransactions;
            $formated_registration->diagnoses = $diagnoses;
            $formated_registration->treatments = $treatments;
            $formated_registration->complaint = $registration->complaint;
            $formated_registration->receipt = $registration->receipt;
            $formated_registration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
            $formated_registration->createdAt = Carbon::parse($registration->created_at);
            $formated_registration->updatedAt = Carbon::parse($registration->updated_at);

            return $formated_registration;
        } catch (\Exception $e) {
            return null;
        }
    }

    function getByPatientId($patientId): array
    {
        try {
            $registrationsCollections = Registration::with(['patient', 'doctor', 'medicineStockTransactions.inventoryOfficer', 'medicineStockTransactions.medicine.unit', 'diagnoses', 'treatments'])->where('patient_id', $patientId)->orderBy('created_at', 'desc')->get();

            $formatedRegistrations = $registrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;

                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                $patient->name = $registration->patient->fullname;
                $patient->age = $registration->patient->getAgeAttribute();
                $patient->telpNumber = $registration->patient->telp_number;
                $patient->address = $registration->patient->address;
                $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
                $patient->gender = $registration->patient->gender;
                $patient->medicalRecordNumber = $registration->patient->medical_record_number;
                $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
                $patient->history = $registration->patient->history;
                $patient->createdAt = $registration->patient->created_at;
                $patient->updatedAt = $registration->patient->updated_at;

                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor->id = $registration->doctor->id;
                    $doctor->username = $registration->doctor->fullname;
                    $doctor->email = $registration->doctor->email;
                    $doctor->fullname = $registration->doctor->fullname;
                    $doctor->role = $registration->doctor->role;
                    $doctor->createdAt = $registration->doctor->created_at;
                    $doctor->updatedAt = $registration->doctor->updated_at;
                }

                $medicineStockTransactions = [];
                if ($registration->medicineStockTransactions) {
                    foreach ($registration->medicineStockTransactions as $transaction) {
                        $medicineStockTransactionDTO = new MedicineStockTransactionDTO();
                        $medicineStockTransactionDTO->id = $transaction->id;

                        // Map inventory officer to UserDTO
                        $inventoryOfficer = new UserDTO();
                        if ($transaction->inventoryOfficer) {
                            $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                        }
                        $medicineStockTransactionDTO->inventoryOfficer = $inventoryOfficer;

                        // Map medicine to MedicineDTO
                        $medicine = new MedicineDTO();
                        if ($transaction->medicine) {
                            $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                        }
                        $medicineStockTransactionDTO->medicine = $medicine;

                        // Map registration to RegistrationDTO (without transactions to avoid circular reference)
                        $medicineStockTransactionDTO->registration = null; // Will be set to parent registration

                        $medicineStockTransactionDTO->type = $transaction->type;
                        $medicineStockTransactionDTO->quantity = $transaction->quantity;
                        $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
                        $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
                        $medicineStockTransactionDTO->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                        $medicineStockTransactionDTO->notes = $transaction->notes;
                        $medicineStockTransactionDTO->createdAt = $transaction->created_at;
                        $medicineStockTransactionDTO->updatedAt = $transaction->updated_at;
                        $medicineStockTransactions[] = $medicineStockTransactionDTO;
                    }
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->medicineStockTransactions = $medicineStockTransactions;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;

                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }

    function insert(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {
        try {
            $registration = new Registration();
            $registration->fill([
                'patient_id' => $registrationDTO->patient->id,
                'receipt' => $registrationDTO->receipt,
                'complaint' => $registrationDTO->complaint,
                'check_date' => $registrationDTO->checkDate,
            ]);
            $registration->save();

            $registrationDTO->id = $registration->id;
            $registrationDTO->receipt = $registration->receipt;
            $registrationDTO->complaint = $registration->complaint;
            $registrationDTO->checkDate = Carbon::parse($registration->check_date);
            $registrationDTO->createdAt = Carbon::parse($registration->created_at);
            $registrationDTO->updatedAt = Carbon::parse($registration->updated_at);

            return $registrationDTO;
        } catch (\Exception $e) {
            return null;
        }
    }


    function edit(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {
        try {
            $updateData = [
                'doctor_id' => $registrationDTO->doctor->id ?? null,
                'receipt' => $registrationDTO->receipt ?? null,
                'check_date' => $registrationDTO->checkDate ?? null,
            ];
            $filteredUpdateData = array_filter($updateData);
            $registrationId = $registrationDTO->id;

            if (!empty($filteredUpdateData)) {
                $edit = Registration::where('id', $registrationId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Registration::find($registrationId);

                    $formatedUpdatedData = new RegistrationDTO();
                    $formatedUpdatedData->id = $updatedData->id;

                    $patient = new PatientDTO();
                    $patient->id = $updatedData->patient_id;

                    $doctor = new UserDTO();
                    $doctor->id = $updatedData->doctor_id;

                    $formatedUpdatedData->patient = $patient;
                    $formatedUpdatedData->doctor = $doctor;
                    $formatedUpdatedData->complaint = $updatedData->complaint;
                    $formatedUpdatedData->receipt = $updatedData->receipt;
                    $formatedUpdatedData->checkDate = Carbon::parse($updatedData->check_date);
                    $formatedUpdatedData->createdAt = Carbon::parse($updatedData->created_at);
                    $formatedUpdatedData->updatedAt = $updatedData->updated_at;

                    return $formatedUpdatedData;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (\Exception $e) {
            dd($e);
            return null;
        }
    }

    function getWhereCheckDateIsNull(): array
    {
        try {
            $nullCheckDateRegistrationsCollections = Registration::with(['patient', 'doctor', 'medicineStockTransactions.inventoryOfficer', 'medicineStockTransactions.medicine.unit'])
                ->whereNull('check_date')
                ->get();

            $formatedRegistrations = $nullCheckDateRegistrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;

                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                $patient->name = $registration->patient->fullname;
                $patient->age = $registration->patient->getAgeAttribute();
                $patient->telpNumber = $registration->patient->telp_number;
                $patient->address = $registration->patient->address;
                $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
                $patient->gender = $registration->patient->gender;
                $patient->medicalRecordNumber = $registration->patient->medical_record_number;
                $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
                $patient->history = $registration->patient->history;
                $patient->createdAt = $registration->patient->created_at;
                $patient->updatedAt = $registration->patient->updated_at;

                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor->id = $registration->doctor->id;
                    $doctor->username = $registration->doctor->fullname;
                    $doctor->email = $registration->doctor->email;
                    $doctor->fullname = $registration->doctor->fullname;
                    $doctor->role = $registration->doctor->role;
                    $doctor->createdAt = $registration->doctor->created_at;
                    $doctor->updatedAt = $registration->doctor->updated_at;
                }

                $medicineStockTransactions = [];
                if ($registration->medicineStockTransactions) {
                    foreach ($registration->medicineStockTransactions as $transaction) {
                        $medicineStockTransactionDTO = new MedicineStockTransactionDTO();
                        $medicineStockTransactionDTO->id = $transaction->id;

                        // Map inventory officer to UserDTO
                        $inventoryOfficer = new UserDTO();
                        if ($transaction->inventoryOfficer) {
                            $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                        }
                        $medicineStockTransactionDTO->inventoryOfficer = $inventoryOfficer;

                        // Map medicine to MedicineDTO
                        $medicine = new MedicineDTO();
                        if ($transaction->medicine) {
                            $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                        }
                        $medicineStockTransactionDTO->medicine = $medicine;
                        $medicineStockTransactionDTO->registration = null;

                        $medicineStockTransactionDTO->type = $transaction->type;
                        $medicineStockTransactionDTO->quantity = $transaction->quantity;
                        $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
                        $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
                        $medicineStockTransactionDTO->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                        $medicineStockTransactionDTO->notes = $transaction->notes;
                        $medicineStockTransactionDTO->createdAt = Carbon::parse($transaction->created_at);
                        $medicineStockTransactionDTO->updatedAt = Carbon::parse($transaction->updated_at);
                        $medicineStockTransactions[] = $medicineStockTransactionDTO;
                    }
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->medicineStockTransactions = $medicineStockTransactions;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;

                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }

    function getWhereCheckDateIsNotNull(): array
    {
        try {
            $notNullCheckDateRegistrationsCollections = Registration::with(['patient', 'doctor', 'medicineStockTransactions.inventoryOfficer', 'medicineStockTransactions.medicine.unit'])
                ->whereNotNull('check_date')
                ->get();

            $formatedRegistrations = $notNullCheckDateRegistrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;

                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                $patient->name = $registration->patient->fullname;
                $patient->age = $registration->patient->getAgeAttribute();
                $patient->telpNumber = $registration->patient->telp_number;
                $patient->address = $registration->patient->address;
                $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
                $patient->gender = $registration->patient->gender;
                $patient->medicalRecordNumber = $registration->patient->medical_record_number;
                $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
                $patient->history = $registration->patient->history;
                $patient->createdAt = $registration->patient->created_at;
                $patient->updatedAt = $registration->patient->updated_at;

                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor = UserRepositoryEloquent::mapUserModelToUserDto($registration->doctor);
                }

                $medicineStockTransactions = [];
                if ($registration->medicineStockTransactions) {
                    foreach ($registration->medicineStockTransactions as $transaction) {
                        $medicineStockTransactionDTO = new MedicineStockTransactionDTO();
                        $medicineStockTransactionDTO->id = $transaction->id;

                        // Map inventory officer to UserDTO
                        $inventoryOfficer = new UserDTO();
                        if ($transaction->inventoryOfficer) {
                            $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                        }
                        $medicineStockTransactionDTO->inventoryOfficer = $inventoryOfficer;

                        // Map medicine to MedicineDTO
                        $medicine = new MedicineDTO();
                        if ($transaction->medicine) {
                            $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                        }
                        $medicineStockTransactionDTO->medicine = $medicine;

                        // Map registration to RegistrationDTO (without transactions to avoid circular reference)
                        $medicineStockTransactionDTO->registration = null; // Will be set to parent registration

                        $medicineStockTransactionDTO->type = $transaction->type;
                        $medicineStockTransactionDTO->quantity = $transaction->quantity;
                        $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
                        $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
                        $medicineStockTransactionDTO->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                        $medicineStockTransactionDTO->notes = $transaction->notes;
                        $medicineStockTransactionDTO->createdAt = $transaction->created_at;
                        $medicineStockTransactionDTO->updatedAt = $transaction->updated_at;
                        $medicineStockTransactions[] = $medicineStockTransactionDTO;
                    }
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->medicineStockTransactions = $medicineStockTransactions;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;

                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }


    function getByRangeDate(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        try {
            $registrationsCollections = Registration::with(['patient', 'doctor', 'medicineStockTransactions.inventoryOfficer', 'medicineStockTransactions.medicine.unit'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $formatedRegistrations = $registrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;

                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                $patient->name = $registration->patient->fullname;
                $patient->age = $registration->patient->getAgeAttribute();
                $patient->telpNumber = $registration->patient->telp_number;
                $patient->address = $registration->patient->address;
                $patient->dateOfBirth = Carbon::parse($registration->patient->date_of_birth);
                $patient->gender = $registration->patient->gender;
                $patient->medicalRecordNumber = $registration->patient->medical_record_number;
                $patient->medicalRecordFileUrl = $registration->patient->medical_record_file_url;
                $patient->history = $registration->patient->history;
                $patient->createdAt = $registration->patient->created_at;
                $patient->updatedAt = $registration->patient->updated_at;

                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor = UserRepositoryEloquent::mapUserModelToUserDto($registration->doctor);
                }

                $medicineStockTransactions = [];
                if ($registration->medicineStockTransactions) {
                    foreach ($registration->medicineStockTransactions as $transaction) {
                        $medicineStockTransactionDTO = new MedicineStockTransactionDTO();
                        $medicineStockTransactionDTO->id = $transaction->id;

                        // Map inventory officer to UserDTO
                        $inventoryOfficer = new UserDTO();
                        if ($transaction->inventoryOfficer) {
                            $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                        }
                        $medicineStockTransactionDTO->inventoryOfficer = $inventoryOfficer;

                        // Map medicine to MedicineDTO
                        $medicine = new MedicineDTO();
                        if ($transaction->medicine) {
                            $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                        }
                        $medicineStockTransactionDTO->medicine = $medicine;

                        // Map registration to RegistrationDTO (without transactions to avoid circular reference)
                        $medicineStockTransactionDTO->registration = null; // Will be set to parent registration

                        $medicineStockTransactionDTO->type = $transaction->type;
                        $medicineStockTransactionDTO->quantity = $transaction->quantity;
                        $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
                        $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
                        $medicineStockTransactionDTO->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                        $medicineStockTransactionDTO->notes = $transaction->notes;
                        $medicineStockTransactionDTO->createdAt = $transaction->created_at;
                        $medicineStockTransactionDTO->updatedAt = $transaction->updated_at;
                        $medicineStockTransactions[] = $medicineStockTransactionDTO;
                    }
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->medicineStockTransactions = $medicineStockTransactions;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = $registration->check_date ? Carbon::parse($registration->check_date) : null;
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;

                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getByUserId(): array
    {
        return $this->getAll();
    }
}
