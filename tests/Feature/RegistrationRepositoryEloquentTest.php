<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repository\RegistrationRepositoryEloquent;
use App\Repository\UserRepositoryEloquent;
use App\Repository\PatientRepositoryEloquent;
use App\DTO\RegistrationDTO;
use App\DTO\PatientDTO;
use App\DTO\UserDTO;
use App\Type\UserRole;
use Illuminate\Support\Carbon;

use function Symfony\Component\Clock\now;

class RegistrationRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected RegistrationRepositoryEloquent $registrationRepository;
    protected UserRepositoryEloquent $userRepository;
    protected PatientRepositoryEloquent $patientRepository;

    protected PatientDTO $patientDTO;
    protected UserDTO $doctorDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationRepository = new RegistrationRepositoryEloquent();
        $this->userRepository = new UserRepositoryEloquent();
        $this->patientRepository = new PatientRepositoryEloquent();

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

            $this->registrationRepository->insert($registrationDTO);
        }

        $registrations = $this->registrationRepository->getAll();
        $this->assertIsArray($registrations);
        $this->assertCount(3, $registrations);
        $this->assertInstanceOf(RegistrationDTO::class, $registrations[0]);
        $this->assertInstanceOf(PatientDTO::class, $registrations[0]->patient);
        // test untuk jika data ada maka data patient id sama doctor id harus ada
        //
        for ($i = 0; $i <= 2; $i++) {
            $this->assertNotNull($registrations[$i]->patient->id);
        }
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

        $insertedRegistrationId = $insertedRegistrationDTO->id;
        $fetchedRegistrationDTO = $this->registrationRepository->getById($insertedRegistrationId);

        $this->assertNotNull($fetchedRegistrationDTO);
        $this->assertEquals($insertedRegistrationDTO->complaint, $fetchedRegistrationDTO->complaint);
        $this->assertEquals($this->patientDTO->id, $fetchedRegistrationDTO->patient->id);
        $this->assertInstanceOf(RegistrationDTO::class, $fetchedRegistrationDTO);
        $this->assertInstanceOf(PatientDTO::class, $fetchedRegistrationDTO->patient);
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

        // Second registration for same patient
        $registrationDTO2 = new RegistrationDTO();
        $registrationDTO2->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO2->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO2->complaint = 'Second visit - Kontrol';

        $insertedRegistration2 = $this->registrationRepository->insert($registrationDTO2);
        $this->assertNotNull($insertedRegistration2);

        // Verify both registrations exist
        $allRegistrations = $this->registrationRepository->getAll();
        $this->assertCount(2, $allRegistrations);
        $this->assertEquals($this->patientDTO->id, $allRegistrations[0]->patient->id);
        $this->assertEquals($this->patientDTO->id, $allRegistrations[1]->patient->id);
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
            \Illuminate\Support\Carbon::setTestNow(); // Kembali ke sekarang
            // dump([
            //     'loop_ke' => $i,
            //     'waktu_palsu_carbon' => \Illuminate\Support\Carbon::now()->toDateTimeString(),
            //     'waktu_di_database' => $inserted->createdAt->toDateTimeString(), // Pastikan properti ini ada di DTO/Model kamu
            // ]);;
        }

        // 2. Data H-0 (Hari ini)
        \Illuminate\Support\Carbon::setTestNow(); // Kembali ke sekarang

        $registrationDTO = new RegistrationDTO();
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Patient Hari Ini - Demam';
        $this->registrationRepository->insert($registrationDTO);

        // 3. Jalankan test filter (H-20 sampai H-1)
        $startDate = \Illuminate\Support\Carbon::now()->subDays(20)->startOfDay();
        $endDate = \Illuminate\Support\Carbon::now()->subDays(1)->endOfDay();

        // Ambil data dari Repository
        $results = $this->registrationRepository->getByRangeDate($startDate, $endDate);
        // dd([
        //     'startDate' => $startDate->toDateTimeString(),
        //     'endDate' => $endDate->toDateTimeString(),
        //     'sample_data_in_db' => \App\Models\Registration::first()->created_at->toDateTimeString()
        // ]);
        // Assert-nya 19 karena data H-0 tidak masuk dalam range (karena endDate adalah H-1)
        $this->assertCount(19, $results);

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
}
