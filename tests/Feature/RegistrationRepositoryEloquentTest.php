<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repository\RegistrationRepositoryEloquent;
use App\Repository\UserRepositoryEloquent;
use App\Repository\PatientRepositoryEloquent;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\DiagnosisRepositoryEloquent;
use App\Repository\TreatmentRepositoryEloquent;
use App\DTO\RegistrationDTO;
use App\DTO\PatientDTO;
use App\DTO\UserDTO;
use App\DTO\MedicineDTO;
use App\DTO\UnitDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\Type\UserRole;
use App\Type\TransactionType;
use Illuminate\Support\Carbon;
use App\Models\Unit;

use function Symfony\Component\Clock\now;

class RegistrationRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected RegistrationRepositoryEloquent $registrationRepository;
    protected UserRepositoryEloquent $userRepository;
    protected PatientRepositoryEloquent $patientRepository;
    protected MedicineRepositoryEloquent $medicineRepository;
    protected MedicineStockTransactionRepositoryEloquent $medicineStockTransactionRepository;
    protected AppointmentRepositoryEloquent $appointmentRepository;
    protected DiagnosisRepositoryEloquent $diagnosisRepository;
    protected TreatmentRepositoryEloquent $treatmentRepository;

    protected PatientDTO $patientDTO;
    protected UserDTO $doctorDTO;
    protected UserDTO $inventoryOfficerDTO;
    protected MedicineDTO $medicineDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationRepository = new RegistrationRepositoryEloquent();
        $this->userRepository = new UserRepositoryEloquent();
        $this->patientRepository = new PatientRepositoryEloquent();
        $this->medicineRepository = new MedicineRepositoryEloquent();
        $this->medicineStockTransactionRepository = new MedicineStockTransactionRepositoryEloquent();
        $this->appointmentRepository = new AppointmentRepositoryEloquent();
        $this->diagnosisRepository = new DiagnosisRepositoryEloquent();
        $this->treatmentRepository = new TreatmentRepositoryEloquent();

        // Create a patient using PatientRepository
        $patientDTO = new PatientDTO();
        $patientDTO->name = 'John Doe';
        $patientDTO->medicalRecordNumber = 'MRN-001';
        $patientDTO->medicalRecordFileUrl = 'files/mrn-001.pdf';
        $patientDTO->address = 'Jl. Test No. 123';
        $patientDTO->telpNumber = '081234567890';
        $patientDTO->dateOfBirth = Carbon::parse('1990-01-01');
        $patientDTO->gender = 'male';
        $patientDTO->history = 'No significant medical history';

        // Insert akan return PatientDTO lengkap dengan id, createdAt, updatedAt
        $this->patientDTO = $this->patientRepository->insert($patientDTO);

        // Create a doctor using UserRepository
        $doctorDTO = new UserDTO();
        $doctorDTO->username = 'doctor1';
        $doctorDTO->fullname = 'Dr. Jane Smith';
        $doctorDTO->email = 'doctor@example.com';
        $doctorDTO->password = bcrypt('password');
        $doctorDTO->role = UserRole::DOCTOR;

        // Insert akan return UserDTO lengkap dengan id, createdAt, updatedAt
        $this->doctorDTO = $this->userRepository->insert($doctorDTO);

        // Create an inventory officer using UserRepository
        $inventoryOfficerDTO = new UserDTO();
        $inventoryOfficerDTO->username = 'inventory1';
        $inventoryOfficerDTO->fullname = 'Inventory Officer';
        $inventoryOfficerDTO->email = 'inventory@example.com';
        $inventoryOfficerDTO->password = bcrypt('password');
        $inventoryOfficerDTO->role = UserRole::INVENTORY_OFFICER;

        $this->inventoryOfficerDTO = $this->userRepository->insert($inventoryOfficerDTO);

        // Create unit first (required for medicine)
        $unit = Unit::create([
            'name' => 'Tablet'
        ]);

        $unitDTO = new UnitDTO();
        $unitDTO->id = $unit->id;
        $unitDTO->name = $unit->name;

        // Create a medicine using MedicineRepository
        $medicineDTO = new MedicineDTO();
        $medicineDTO->name = 'Paracetamol';
        $medicineDTO->stock = 100;
        $medicineDTO->unit = $unitDTO;

        $this->medicineDTO = $this->medicineRepository->insert($medicineDTO);
    }

    // Helper method to create medicine stock transaction
    protected function createMedicineStockTransaction(RegistrationDTO $registrationDTO): ?MedicineStockTransactionDTO
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = $registrationDTO; // Changed: registrationId is now RegistrationDTO or null, not string
        $transactionDTO->type = TransactionType::OUT;
        $transactionDTO->quantity = 2;
        $transactionDTO->expiredDate = Carbon::now()->addYears(2);
        $transactionDTO->notes = 'Medicine for patient';

        return $this->medicineStockTransactionRepository->insert($transactionDTO);
    }

    // test untuk insert registration
    public function testInsertRegistrationSuccess(): void
    {
        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);
        $this->assertEquals('Sakit kepala dan demam', $insertedRegistrationDTO->complaint);
        $this->assertEquals($this->patientDTO->id, $insertedRegistrationDTO->patient->id);
        $this->assertInstanceOf(RegistrationDTO::class, $insertedRegistrationDTO);
        $this->assertInstanceOf(PatientDTO::class, $insertedRegistrationDTO->patient);
    }

    // test insert registration gagal karena patient tidak ada
    public function testInsertRegistrationWithNonExistentPatient(): void
    {
        $patientDTO = new PatientDTO();
        $patientDTO->id = '9999'; // Non-existent patient ID\
        $patientDTO->name = 'Non Existent';
        $patientDTO->medicalRecordNumber = 'MRN-9999';
        $patientDTO->medicalRecordFileUrl = 'files/mrn-9999.pdf';
        $patientDTO->address = 'Unknown';
        $patientDTO->telpNumber = '0000000000';
        $patientDTO->dateOfBirth = Carbon::parse('2000-01-01');
        $patientDTO->age = 24;
        $patientDTO->gender = 'unknown';
        $patientDTO->history = 'N/A';

        $doctorDTO = new UserDTO();
        $doctorDTO->id = $this->doctorDTO->id;
        $doctorDTO->username = $this->doctorDTO->username;
        $doctorDTO->fullname = $this->doctorDTO->fullname;
        $doctorDTO->email = $this->doctorDTO->email;
        $doctorDTO->password = $this->doctorDTO->password;
        $doctorDTO->role = $this->doctorDTO->role;

        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $patientDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';
        $registrationDTO->checkDate = Carbon::now();
        $registrationDTO->doctor = $doctorDTO;

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);
        $this->assertNull($insertedRegistrationDTO);
    }

    // test untuk get all registrations
    public function testGetAllRegistrations(): void
    {
        // Insert some registrations first
        for ($i = 1; $i <= 3; $i++) {
            $registrationDTO = new RegistrationDTO();
            $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
            $registrationDTO->complaint = "Complaint $i";

            $inserted = $this->registrationRepository->insert($registrationDTO);
            
            // Add medicine stock transaction for each registration
            if ($i <= 2) { // Only add transaction for first 2 registrations
                $this->createMedicineStockTransaction($inserted);
            }
        }

        $registrations = $this->registrationRepository->getAll();
        $this->assertIsArray($registrations);
        $this->assertCount(3, $registrations);
        $this->assertInstanceOf(RegistrationDTO::class, $registrations[0]);
        $this->assertInstanceOf(PatientDTO::class, $registrations[0]->patient);
        
        // Test medicineStockTransactions array exists
        for ($i = 0; $i <= 2; $i++) {
            $this->assertNotNull($registrations[$i]->patient->id);
            $this->assertIsArray($registrations[$i]->medicineStockTransactions);
        }
        
        // Test first 2 registrations have medicine transactions
        $this->assertCount(1, $registrations[0]->medicineStockTransactions);
        $this->assertCount(1, $registrations[1]->medicineStockTransactions);
        $this->assertCount(0, $registrations[2]->medicineStockTransactions); // Third one should be empty
        
        // Verify the transaction data structure with objects
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $registrations[0]->medicineStockTransactions[0]);
        
        // Check inventoryOfficer is UserDTO object
        $this->assertInstanceOf(UserDTO::class, $registrations[0]->medicineStockTransactions[0]->inventoryOfficer);
        $this->assertEquals($this->inventoryOfficerDTO->id, $registrations[0]->medicineStockTransactions[0]->inventoryOfficer->id);
        
        // Check medicine is MedicineDTO object
        $this->assertInstanceOf(MedicineDTO::class, $registrations[0]->medicineStockTransactions[0]->medicine);
        $this->assertEquals($this->medicineDTO->id, $registrations[0]->medicineStockTransactions[0]->medicine->id);
        $this->assertEquals($this->medicineDTO->name, $registrations[0]->medicineStockTransactions[0]->medicine->name);
        
        $this->assertEquals(TransactionType::OUT, $registrations[0]->medicineStockTransactions[0]->type);
    }

    // test untuk getall registrations ketika tidak ada data
    public function testGetAllRegistrationsWhenNoData(): void
    {
        $registrations = $this->registrationRepository->getAll();
        $this->assertIsArray($registrations);
        $this->assertCount(0, $registrations);
    }

    // test getById registration
    public function testGetByIdRegistration(): void
    {
        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Batuk dan pilek';
        $registrationDTO->receipt = 'Batuk dan pilek';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);
        $this->assertNotNull($insertedRegistrationDTO);

        // Add medicine stock transaction
        $this->createMedicineStockTransaction($insertedRegistrationDTO);

        $insertedRegistrationId = $insertedRegistrationDTO->id;
        $fetchedRegistrationDTO = $this->registrationRepository->getById($insertedRegistrationId);

        $this->assertNotNull($fetchedRegistrationDTO);
        $this->assertEquals($insertedRegistrationDTO->complaint, $fetchedRegistrationDTO->complaint);
        $this->assertEquals($this->patientDTO->id, $fetchedRegistrationDTO->patient->id);
        $this->assertInstanceOf(RegistrationDTO::class, $fetchedRegistrationDTO);
        $this->assertInstanceOf(PatientDTO::class, $fetchedRegistrationDTO->patient);
        
        // Test medicineStockTransactions with objects
        $this->assertIsArray($fetchedRegistrationDTO->medicineStockTransactions);
        $this->assertCount(1, $fetchedRegistrationDTO->medicineStockTransactions);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $fetchedRegistrationDTO->medicineStockTransactions[0]);
        
        // Check inventoryOfficer is UserDTO object
        $this->assertInstanceOf(UserDTO::class, $fetchedRegistrationDTO->medicineStockTransactions[0]->inventoryOfficer);
        $this->assertEquals($this->inventoryOfficerDTO->id, $fetchedRegistrationDTO->medicineStockTransactions[0]->inventoryOfficer->id);
        
        // Check medicine is MedicineDTO object
        $this->assertInstanceOf(MedicineDTO::class, $fetchedRegistrationDTO->medicineStockTransactions[0]->medicine);
        $this->assertEquals($this->medicineDTO->id, $fetchedRegistrationDTO->medicineStockTransactions[0]->medicine->id);
        
        $this->assertEquals($insertedRegistrationId, $fetchedRegistrationDTO->id);
    }

    

    // test getById registration yang tidak ada
    public function testGetByIdNonExistentRegistration(): void
    {
        $nonExistentRegistrationId = '9999';
        $fetchedRegistrationDTO = $this->registrationRepository->getById($nonExistentRegistrationId);
        $this->assertNull($fetchedRegistrationDTO);
    }

    // test insert registration dengan multiple patients
    public function testInsertMultipleRegistrationsForSamePatient(): void
    {
        // First registration
        $registrationDTO1 = new RegistrationDTO();
        $registrationDTO1->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO1->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO1->complaint = 'First visit - Demam';

        $insertedRegistration1 = $this->registrationRepository->insert($registrationDTO1);
        $this->assertNotNull($insertedRegistration1);
        
        // Add transaction for first registration
        $this->createMedicineStockTransaction($insertedRegistration1);

        // Second registration for same patient
        $registrationDTO2 = new RegistrationDTO();
        $registrationDTO2->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO2->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO2->complaint = 'Second visit - Kontrol';

        $insertedRegistration2 = $this->registrationRepository->insert($registrationDTO2);
        $this->assertNotNull($insertedRegistration2);
        
        // Add transaction for second registration
        $this->createMedicineStockTransaction($insertedRegistration2);

        // Verify both registrations exist
        $allRegistrations = $this->registrationRepository->getAll();
        $this->assertCount(2, $allRegistrations);
        $this->assertEquals($this->patientDTO->id, $allRegistrations[0]->patient->id);
        $this->assertEquals($this->patientDTO->id, $allRegistrations[1]->patient->id);
        
        // Verify both have medicine transactions
        $this->assertIsArray($allRegistrations[0]->medicineStockTransactions);
        $this->assertIsArray($allRegistrations[1]->medicineStockTransactions);
        $this->assertCount(1, $allRegistrations[0]->medicineStockTransactions);
        $this->assertCount(1, $allRegistrations[1]->medicineStockTransactions);
    }

    // test untuk insert registration dengan berbagai patient
    public function testInsertRegistrationWithDifferentPatients(): void
    {
        // Create second patient using PatientRepository
        $patientDTO2 = new PatientDTO();
        $patientDTO2->name = 'Jane Smith';
        $patientDTO2->medicalRecordNumber = 'MRN-002';
        $patientDTO2->medicalRecordFileUrl = 'files/mrn-002.pdf';
        $patientDTO2->address = 'Jl. Test No. 456';
        $patientDTO2->telpNumber = '081234567891';
        $patientDTO2->dateOfBirth = Carbon::parse('1985-05-15');
        $patientDTO2->gender = 'female';
        $patientDTO2->history = 'Has diabetes';

        // Insert akan return PatientDTO lengkap
        $insertedPatient2 = $this->patientRepository->insert($patientDTO2);

        // First registration with first patient
        $registrationDTO1 = new RegistrationDTO();
        $registrationDTO1->patient = $this->patientDTO; // Gunakan DTO dari setUp
        $registrationDTO1->doctor = $this->doctorDTO; // Gunakan DTO dari setUp
        $registrationDTO1->complaint = 'Patient 1 - Demam';

        $insertedRegistration1 = $this->registrationRepository->insert($registrationDTO1);
        $this->assertNotNull($insertedRegistration1);

        // Second registration with second patient
        $registrationDTO2 = new RegistrationDTO();
        $registrationDTO2->patient = $insertedPatient2; // Gunakan DTO hasil insert
        $registrationDTO2->doctor = $this->doctorDTO; // Gunakan DTO hasil insert
        $registrationDTO2->complaint = 'Patient 2 - Sakit perut';

        $insertedRegistration2 = $this->registrationRepository->insert($registrationDTO2);
        $this->assertNotNull($insertedRegistration2);

        // Verify both registrations exist with different patients
        $allRegistrations = $this->registrationRepository->getAll();
        $this->assertCount(2, $allRegistrations);
        $this->assertEquals($this->patientDTO->id, $allRegistrations[0]->patient->id);
        $this->assertEquals($insertedPatient2->id, $allRegistrations[1]->patient->id);
        $this->assertNotEquals($allRegistrations[0]->patient->id, $allRegistrations[1]->patient->id);
    }

    // test untuk verifikasi doctor tersedia
    public function testDoctorIsAvailable(): void
    {
        $this->assertNotNull($this->doctorDTO);
        $this->assertNotNull($this->doctorDTO->id);
        $this->assertEquals('doctor1', $this->doctorDTO->username);
        $this->assertEquals('Dr. Jane Smith', $this->doctorDTO->fullname);
        $this->assertEquals(UserRole::DOCTOR, $this->doctorDTO->role);
        $this->assertNotNull($this->doctorDTO->createdAt);
        $this->assertNotNull($this->doctorDTO->updatedAt);
    }

    // test untuk verifikasi patient tersedia
    public function testPatientIsAvailable(): void
    {
        $this->assertNotNull($this->patientDTO);
        $this->assertNotNull($this->patientDTO->id);
        $this->assertEquals('John Doe', $this->patientDTO->name);
        $this->assertEquals('MRN-001', $this->patientDTO->medicalRecordNumber);
        $this->assertNotNull($this->patientDTO->createdAt);
        $this->assertNotNull($this->patientDTO->updatedAt);
    }


    // test getByRangeDate
    public function testGetByRangeDate(): void
    {
        // 1. Insert 19 data (H-20 sampai H-2)
        for ($i = 20; $i > 1; $i--) {
            // Tentukan waktu palsu
            $fakeDate = \Illuminate\Support\Carbon::now()->subDays($i);
            \Illuminate\Support\Carbon::setTestNow($fakeDate); // INI YANG PENTING
            $registrationDTO = new RegistrationDTO();
            $registrationDTO->patient = $this->patientDTO;
            $registrationDTO->doctor = $this->doctorDTO;
            $registrationDTO->complaint = 'Patient ' . $i . ' - Demam';

            $inserted = $this->registrationRepository->insert($registrationDTO);
            
            // Add medicine transaction for some registrations
            if ($i % 3 == 0) { // Add transaction every 3rd registration
                $this->createMedicineStockTransaction($inserted);
            }
            
            \Illuminate\Support\Carbon::setTestNow(); // Kembali ke sekarang
        }

        // 2. Data H-0 (Hari ini)
        \Illuminate\Support\Carbon::setTestNow(); // Kembali ke sekarang

        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Patient Hari Ini - Demam';
        $insertedToday = $this->registrationRepository->insert($registrationDTO);
        $this->createMedicineStockTransaction($insertedToday);

        // 3. Jalankan test filter (H-20 sampai H-1)
        $startDate = \Illuminate\Support\Carbon::now()->subDays(20)->startOfDay();
        $endDate = \Illuminate\Support\Carbon::now()->subDays(1)->endOfDay();

        // Ambil data dari Repository
        $results = $this->registrationRepository->getByRangeDate($startDate, $endDate);
        
        // Assert-nya 19 karena data H-0 tidak masuk dalam range (karena endDate adalah H-1)
        $this->assertCount(19, $results);
        
        // Verify medicineStockTransactions exists in results
        foreach ($results as $registration) {
            $this->assertIsArray($registration->medicineStockTransactions);
        }
        
        // Count how many have transactions (should be 6: days 18, 15, 12, 9, 6, 3)
        $withTransactions = array_filter($results, function($reg) {
            return count($reg->medicineStockTransactions) > 0;
        });
        $this->assertCount(6, $withTransactions);

        // SANGAT PENTING: Selalu bersihkan waktu palsu
        \Illuminate\Support\Carbon::setTestNow();
    }


    // test untuk edit data
    public function testEditRegistration(): void
    {
        // Insert initial registration
        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala';
        $registrationDTO->receipt = 'minum baigon';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);
        $this->assertNotNull($insertedRegistrationDTO);
        
        // Add medicine transaction
        $this->createMedicineStockTransaction($insertedRegistrationDTO);

        // Edit the registration
        $insertedRegistrationDTO->receipt = 'Makan tai';
        $editedRegistrationDTO = $this->registrationRepository->edit($insertedRegistrationDTO);

        $this->assertNotNull($editedRegistrationDTO);
        $this->assertEquals('Makan tai', $editedRegistrationDTO->receipt);
        $this->assertEquals($this->patientDTO->id, $editedRegistrationDTO->patient->id);
        $this->assertEquals($this->doctorDTO->id, $editedRegistrationDTO->doctor->id);
        $this->assertInstanceOf(RegistrationDTO::class, $editedRegistrationDTO);
        $this->assertInstanceOf(PatientDTO::class, $editedRegistrationDTO->patient);
    }
    
    // New test: Get registrations where check_date is null with medicine transactions
    public function testGetWhereCheckDateIsNullWithMedicineTransactions(): void
    {
        // Insert registrations with null check_date
        for ($i = 1; $i <= 3; $i++) {
            $registrationDTO = new RegistrationDTO();
            $registrationDTO->patient = $this->patientDTO;
            $registrationDTO->complaint = "Complaint $i";
            $registrationDTO->checkDate = null; // Explicitly null

            $inserted = $this->registrationRepository->insert($registrationDTO);
            
            // Add medicine transaction for first 2
            if ($i <= 2) {
                $this->createMedicineStockTransaction($inserted);
            }
        }
        
        // Insert one with check_date (should not appear in results)
        $registrationWithDate = new RegistrationDTO();
        $registrationWithDate->patient = $this->patientDTO;
        $registrationWithDate->complaint = "With date";
        $registrationWithDate->checkDate = Carbon::now();
        $this->registrationRepository->insert($registrationWithDate);

        $results = $this->registrationRepository->getWhereCheckDateIsNull();
        
        $this->assertIsArray($results);
        $this->assertCount(3, $results);
        
        // Verify medicineStockTransactions
        foreach ($results as $registration) {
            $this->assertIsArray($registration->medicineStockTransactions);
        }
        
        $this->assertCount(1, $results[0]->medicineStockTransactions);
        $this->assertCount(1, $results[1]->medicineStockTransactions);
        $this->assertCount(0, $results[2]->medicineStockTransactions);
        
        // Verify structure with objects
        $this->assertInstanceOf(UserDTO::class, $results[0]->medicineStockTransactions[0]->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $results[0]->medicineStockTransactions[0]->medicine);
    }
    
    // New test: Get registrations where check_date is not null with medicine transactions
    public function testGetWhereCheckDateIsNotNullWithMedicineTransactions(): void
    {
        // Insert registrations with check_date
        for ($i = 1; $i <= 3; $i++) {
            $registrationDTO = new RegistrationDTO();
            $registrationDTO->patient = $this->patientDTO;
            $registrationDTO->doctor = $this->doctorDTO;
            $registrationDTO->complaint = "Complaint $i";
            $registrationDTO->checkDate = Carbon::now()->subDays($i);

            $inserted = $this->registrationRepository->insert($registrationDTO);
            
            // Add medicine transaction for all
            $this->createMedicineStockTransaction($inserted);
        }
        
        // Insert one with null check_date (should not appear in results)
        $registrationWithoutDate = new RegistrationDTO();
        $registrationWithoutDate->patient = $this->patientDTO;
        $registrationWithoutDate->complaint = "Without date";
        $registrationWithoutDate->checkDate = null;
        $this->registrationRepository->insert($registrationWithoutDate);

        $results = $this->registrationRepository->getWhereCheckDateIsNotNull();
        
        $this->assertIsArray($results);
        $this->assertCount(3, $results);
        
        // Verify all have medicineStockTransactions with correct structure
        foreach ($results as $registration) {
            $this->assertIsArray($registration->medicineStockTransactions);
            $this->assertCount(1, $registration->medicineStockTransactions);
            $this->assertInstanceOf(MedicineStockTransactionDTO::class, $registration->medicineStockTransactions[0]);
            
            // Verify objects
            $this->assertInstanceOf(UserDTO::class, $registration->medicineStockTransactions[0]->inventoryOfficer);
            $this->assertInstanceOf(MedicineDTO::class, $registration->medicineStockTransactions[0]->medicine);
        }
    }

    // Test untuk verifikasi diagnosis dan treatment yang di-attach pada registration
    public function testGetByIdWithDiagnosesAndTreatments(): void
    {
        // Insert registration
        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Demam tinggi dan batuk';
        $registrationDTO->checkDate = Carbon::now();

        $insertedRegistration = $this->registrationRepository->insert($registrationDTO);
        $this->assertNotNull($insertedRegistration);

        // Create diagnoses using DiagnosisRepository
        $diagnosisDTO1 = new \App\DTO\DiagnosisDTO();
        $diagnosisDTO1->icdCode = 'A000';
        $diagnosisDTO1->diseaseName = 'Cholera due to Vibrio cholerae';
        $insertedDiagnosis1 = $this->diagnosisRepository->insert($diagnosisDTO1);

        $diagnosisDTO2 = new \App\DTO\DiagnosisDTO();
        $diagnosisDTO2->icdCode = 'A001';
        $diagnosisDTO2->diseaseName = 'Cholera due to Vibrio cholerae El Tor';
        $insertedDiagnosis2 = $this->diagnosisRepository->insert($diagnosisDTO2);

        $diagnosisDTO3 = new \App\DTO\DiagnosisDTO();
        $diagnosisDTO3->icdCode = 'J00';
        $diagnosisDTO3->diseaseName = 'Acute nasopharyngitis (common cold)';
        $insertedDiagnosis3 = $this->diagnosisRepository->insert($diagnosisDTO3);

        // Create treatments using TreatmentRepository
        $treatmentDTO1 = new \App\DTO\TreatmentDTO();
        $treatmentDTO1->treatmentCode = 'T001';
        $treatmentDTO1->treatmentName = 'Konsultasi Dokter Umum';
        $insertedTreatment1 = $this->treatmentRepository->insert($treatmentDTO1);

        $treatmentDTO2 = new \App\DTO\TreatmentDTO();
        $treatmentDTO2->treatmentCode = 'T002';
        $treatmentDTO2->treatmentName = 'Pemeriksaan Fisik';
        $insertedTreatment2 = $this->treatmentRepository->insert($treatmentDTO2);

        $treatmentDTO3 = new \App\DTO\TreatmentDTO();
        $treatmentDTO3->treatmentCode = 'T003';
        $treatmentDTO3->treatmentName = 'Pemberian Obat Oral';
        $insertedTreatment3 = $this->treatmentRepository->insert($treatmentDTO3);

        // Attach diagnoses to registration using AppointmentRepository
        $attachDiagnoses = $this->appointmentRepository->attachManyDiagnosesToRegistration(
            $insertedRegistration,
            [$insertedDiagnosis1, $insertedDiagnosis2, $insertedDiagnosis3]
        );
        $this->assertTrue($attachDiagnoses, 'Failed to attach diagnoses');

        // Attach treatments to registration using AppointmentRepository
        $attachTreatments = $this->appointmentRepository->attachManyTreatmentsToRegistration(
            $insertedRegistration,
            [$insertedTreatment1, $insertedTreatment2, $insertedTreatment3]
        );
        $this->assertTrue($attachTreatments, 'Failed to attach treatments');

        // Get registration by ID
        $fetchedRegistration = $this->registrationRepository->getById($insertedRegistration->id);

        $this->assertNotNull($fetchedRegistration);
        $this->assertInstanceOf(RegistrationDTO::class, $fetchedRegistration);

        // Verify diagnoses
        // dd($fetchedRegistration->diagnoses);
        $this->assertIsArray($fetchedRegistration->diagnoses);
        $this->assertCount(3, $fetchedRegistration->diagnoses, 'Should have 3 diagnoses attached');

        // Verify each diagnosis data
        $diagnosisIds = array_column($fetchedRegistration->diagnoses, 'id');
        $this->assertContains($insertedDiagnosis1->id, $diagnosisIds);
        $this->assertContains($insertedDiagnosis2->id, $diagnosisIds);
        $this->assertContains($insertedDiagnosis3->id, $diagnosisIds);

        // Verify diagnosis ICD codes
        $icdCodes = array_column($fetchedRegistration->diagnoses, 'icdCode');
        $this->assertContains('A000', $icdCodes);
        $this->assertContains('A001', $icdCodes);
        $this->assertContains('J00', $icdCodes);

        // Verify treatments
        $this->assertIsArray($fetchedRegistration->treatments);
        $this->assertCount(3, $fetchedRegistration->treatments, 'Should have 3 treatments attached');

        // Verify each treatment data
        $treatmentIds = array_column($fetchedRegistration->treatments, 'id');
        $this->assertContains($insertedTreatment1->id, $treatmentIds);
        $this->assertContains($insertedTreatment2->id, $treatmentIds);
        $this->assertContains($insertedTreatment3->id, $treatmentIds);

        // Verify treatment codes
        $treatmentCodes = array_column($fetchedRegistration->treatments, 'treatmentCode');
        $this->assertContains('T001', $treatmentCodes);
        $this->assertContains('T002', $treatmentCodes);
        $this->assertContains('T003', $treatmentCodes);

        // Verify treatment names
        $treatmentNames = array_column($fetchedRegistration->treatments, 'treatmentName');
        $this->assertContains('Konsultasi Dokter Umum', $treatmentNames);
        $this->assertContains('Pemeriksaan Fisik', $treatmentNames);
        $this->assertContains('Pemberian Obat Oral', $treatmentNames);
    }

    // Test 1: Get registrations by patient_id with multiple registrations
    public function testGetByPatientIdWithMultipleRegistrations(): void
    {
        // Create multiple registrations for the same patient at different times
        $registrations = [];
        
        // Registration 1 - 10 days ago
        \Illuminate\Support\Carbon::setTestNow(\Illuminate\Support\Carbon::now()->subDays(10));
        $registrationDTO1 = new RegistrationDTO();
        $registrationDTO1->patient = $this->patientDTO;
        $registrationDTO1->doctor = $this->doctorDTO;
        $registrationDTO1->complaint = 'First visit - Demam';
        $registrationDTO1->receipt = 'Paracetamol';
        $registrationDTO1->checkDate = Carbon::now();
        $inserted1 = $this->registrationRepository->insert($registrationDTO1);
        $this->createMedicineStockTransaction($inserted1);
        $registrations[] = $inserted1;
        \Illuminate\Support\Carbon::setTestNow();

        // Registration 2 - 5 days ago
        \Illuminate\Support\Carbon::setTestNow(\Illuminate\Support\Carbon::now()->subDays(5));
        $registrationDTO2 = new RegistrationDTO();
        $registrationDTO2->patient = $this->patientDTO;
        $registrationDTO2->doctor = $this->doctorDTO;
        $registrationDTO2->complaint = 'Second visit - Batuk';
        $registrationDTO2->receipt = 'OBH';
        $registrationDTO2->checkDate = Carbon::now();
        $inserted2 = $this->registrationRepository->insert($registrationDTO2);
        $this->createMedicineStockTransaction($inserted2);
        $registrations[] = $inserted2;
        \Illuminate\Support\Carbon::setTestNow();

        // Registration 3 - Today (most recent)
        $registrationDTO3 = new RegistrationDTO();
        $registrationDTO3->patient = $this->patientDTO;
        $registrationDTO3->doctor = $this->doctorDTO;
        $registrationDTO3->complaint = 'Third visit - Kontrol';
        $registrationDTO3->receipt = 'Vitamin';
        $registrationDTO3->checkDate = Carbon::now();
        $inserted3 = $this->registrationRepository->insert($registrationDTO3);
        $this->createMedicineStockTransaction($inserted3);
        $registrations[] = $inserted3;

        // Create registration for different patient (should not appear in results)
        $otherPatientDTO = new PatientDTO();
        $otherPatientDTO->name = 'Other Patient';
        $otherPatientDTO->medicalRecordNumber = 'MRN-999';
        $otherPatientDTO->medicalRecordFileUrl = 'files/mrn-999.pdf';
        $otherPatientDTO->address = 'Jl. Other No. 999';
        $otherPatientDTO->telpNumber = '089999999999';
        $otherPatientDTO->dateOfBirth = Carbon::parse('1995-01-01');
        $otherPatientDTO->gender = 'male';
        $otherPatientDTO->history = 'No history';
        $insertedOtherPatient = $this->patientRepository->insert($otherPatientDTO);

        $otherRegistrationDTO = new RegistrationDTO();
        $otherRegistrationDTO->patient = $insertedOtherPatient;
        $otherRegistrationDTO->doctor = $this->doctorDTO;
        $otherRegistrationDTO->complaint = 'Other patient complaint';
        $this->registrationRepository->insert($otherRegistrationDTO);

        // Get registrations by patient_id
        $results = $this->registrationRepository->getByPatientId($this->patientDTO->id);

        // Verify results
        $this->assertIsArray($results);
        $this->assertCount(3, $results, 'Should have 3 registrations for this patient');

        // Verify all results are RegistrationDTO instances
        foreach ($results as $result) {
            $this->assertInstanceOf(RegistrationDTO::class, $result);
            $this->assertEquals($this->patientDTO->id, $result->patient->id, 'All results should belong to the same patient');
        }

        // Verify first item is the most recent (created_at terbaru)
        $this->assertEquals($inserted3->id, $results[0]->id, 'First item should be the most recent registration');
        $this->assertEquals('Third visit - Kontrol', $results[0]->complaint);
        $this->assertEquals('Vitamin', $results[0]->receipt);
        $this->assertInstanceOf(PatientDTO::class, $results[0]->patient);
        $this->assertInstanceOf(UserDTO::class, $results[0]->doctor);

        // Verify second item
        $this->assertEquals($inserted2->id, $results[1]->id);
        $this->assertEquals('Second visit - Batuk', $results[1]->complaint);
        $this->assertEquals('OBH', $results[1]->receipt);

        // Verify third item (oldest)
        $this->assertEquals($inserted1->id, $results[2]->id);
        $this->assertEquals('First visit - Demam', $results[2]->complaint);
        $this->assertEquals('Paracetamol', $results[2]->receipt);

        // Verify all have medicine stock transactions
        foreach ($results as $result) {
            $this->assertIsArray($result->medicineStockTransactions);
            $this->assertCount(1, $result->medicineStockTransactions);
            $this->assertInstanceOf(MedicineStockTransactionDTO::class, $result->medicineStockTransactions[0]);
            $this->assertInstanceOf(UserDTO::class, $result->medicineStockTransactions[0]->inventoryOfficer);
            $this->assertInstanceOf(MedicineDTO::class, $result->medicineStockTransactions[0]->medicine);
        }

        // Verify created_at timestamps are in descending order (newest first)
        $this->assertGreaterThan(
            $results[1]->createdAt->timestamp,
            $results[0]->createdAt->timestamp,
            'First item should have newer created_at than second'
        );
        $this->assertGreaterThan(
            $results[2]->createdAt->timestamp,
            $results[1]->createdAt->timestamp,
            'Second item should have newer created_at than third'
        );

        // Verify check_date is not null for all
        foreach ($results as $result) {
            $this->assertNotNull($result->checkDate);
            $this->assertInstanceOf(\Carbon\CarbonInterface::class, $result->checkDate);
        }

        // Verify patient data integrity
        foreach ($results as $result) {
            $this->assertEquals('John Doe', $result->patient->name);
            $this->assertEquals('MRN-001', $result->patient->medicalRecordNumber);
        }

        // Clean up
        \Illuminate\Support\Carbon::setTestNow();
    }

    // Test 2: Get registrations by patient_id with no data
    public function testGetByPatientIdWithNoRegistrations(): void
    {
        // Create a patient without any registrations
        $patientDTO = new PatientDTO();
        $patientDTO->name = 'Patient Without Registrations';
        $patientDTO->medicalRecordNumber = 'MRN-888';
        $patientDTO->medicalRecordFileUrl = 'files/mrn-888.pdf';
        $patientDTO->address = 'Jl. Empty No. 888';
        $patientDTO->telpNumber = '088888888888';
        $patientDTO->dateOfBirth = Carbon::parse('1992-06-15');
        $patientDTO->gender = 'female';
        $patientDTO->history = 'No history';
        $insertedPatient = $this->patientRepository->insert($patientDTO);

        // Create registrations for different patient (should not appear in results)
        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO; // Different patient
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Other patient complaint';
        $this->registrationRepository->insert($registrationDTO);

        // Get registrations by patient_id (should return empty array)
        $results = $this->registrationRepository->getByPatientId($insertedPatient->id);

        // Verify results
        $this->assertIsArray($results);
        $this->assertCount(0, $results, 'Should have no registrations for this patient');
        $this->assertEmpty($results);
    }
}
