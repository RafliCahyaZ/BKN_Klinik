<?php

namespace Tests\Feature;

use App\DTO\PatientDTO;
use App\Repository\PatientRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientRepositoryEloquentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected PatientRepositoryEloquent $patientRepository;

    protected array $patientData = [
        'fullname' => 'John Doe',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->patientRepository = new PatientRepositoryEloquent;

        $this->patientData = [
            'fullname' => 'John Doe',
            'gender' => 'male',
            'address' => '123 Main St',
            'date_of_birth' => '1990-01-01',
            'telp_number' => '1234567890',
            'history' => 'No significant history',
            'medical_record_file_url' => 'http://example.com/record.pdf',
            'medical_record_number' => 'MRN123456',
        ];
    }

    // test untuk insert patient
    public function test_insert_patient_success(): void
    {
        $patientDTO = new PatientDTO;
        $patientDTO->name = $this->patientData['fullname'];
        $patientDTO->gender = $this->patientData['gender'];
        $patientDTO->address = $this->patientData['address'];
        $patientDTO->dateOfBirth = Carbon::parse($this->patientData['date_of_birth']);
        $patientDTO->telpNumber = $this->patientData['telp_number'];
        $patientDTO->history = $this->patientData['history'];
        $patientDTO->medicalRecordFileUrl = $this->patientData['medical_record_file_url'];
        $patientDTO->medicalRecordNumber = $this->patientData['medical_record_number'];
        $insertedPatientDTO = $this->patientRepository->insert($patientDTO);
        $this->assertNotNull($insertedPatientDTO);
        $this->assertEquals($this->patientData['fullname'], $insertedPatientDTO->name);
        $this->assertEquals($this->patientData['gender'], $insertedPatientDTO->gender);

        // cek return value adalah instance of PatientDTO
        $this->assertInstanceOf(PatientDTO::class, $insertedPatientDTO);
    }

    // test untuk insert patient gagal ketika data unik sudah ada
    public function test_insert_patient_failed_when_unique_data_exists(): void
    {
        $patientDTO = new PatientDTO;
        $patientDTO->name = $this->patientData['fullname'];
        $patientDTO->gender = $this->patientData['gender'];
        $patientDTO->address = $this->patientData['address'];
        $patientDTO->dateOfBirth = Carbon::parse($this->patientData['date_of_birth']);
        $patientDTO->telpNumber = $this->patientData['telp_number'];
        $patientDTO->history = $this->patientData['history'];
        $patientDTO->medicalRecordFileUrl = $this->patientData['medical_record_file_url'];
        $patientDTO->medicalRecordNumber = $this->patientData['medical_record_number'];

        $firstInsert = $this->patientRepository->insert($patientDTO);
        $this->assertNotNull($firstInsert);

        $duplicatePatientDTO = new PatientDTO;
        $duplicatePatientDTO->name = 'Jane Doe';
        $duplicatePatientDTO->gender = 'female';
        $duplicatePatientDTO->address = '456 Oak St';
        $duplicatePatientDTO->dateOfBirth = Carbon::parse('1985-05-15');
        $duplicatePatientDTO->telpNumber = '0987654321';
        $duplicatePatientDTO->history = 'Some history';
        $duplicatePatientDTO->medicalRecordFileUrl = 'http://example.com/record2.pdf';
        $duplicatePatientDTO->medicalRecordNumber = $this->patientData['medical_record_number']; // Same as first patient

        $this->expectException(\Illuminate\Database\QueryException::class);
        $secondInsert = $this->patientRepository->insert($duplicatePatientDTO);
        $this->assertEquals(null, $secondInsert);
    }

    // test untuk get all patients
    public function test_get_all_patients(): void
    {
        // Insert some patients first
        for ($i = 1; $i <= 3; $i++) {
            $patientDTO = new PatientDTO;
            $patientDTO->name = "Patient $i";
            $patientDTO->gender = ($i % 2 == 0) ? 'female' : 'male';
            $patientDTO->address = "$i Main St";
            $patientDTO->dateOfBirth = Carbon::parse("1990-01-0$i");
            $patientDTO->telpNumber = "123456789$i";
            $patientDTO->history = "History $i";
            $patientDTO->medicalRecordFileUrl = "http://example.com/record$i.pdf";
            $patientDTO->medicalRecordNumber = "MRN12345$i";

            $this->patientRepository->insert($patientDTO);
        }

        $patients = $this->patientRepository->getAll();
        $this->assertIsArray($patients);
        $this->assertCount(3, $patients);
    }

    // test untuk getall patients ketika tidak ada data
    public function test_get_all_patients_when_no_data(): void
    {
        $patients = $this->patientRepository->getAll();
        // cek patients adalah array kosong
        $this->assertIsArray($patients);
        $this->assertCount(0, $patients);
    }

    // test untuk edit patient
    public function test_edit_patient(): void
    {
        $patientDTO = new PatientDTO;
        $patientDTO->name = $this->patientData['fullname'];
        $patientDTO->gender = $this->patientData['gender'];
        $patientDTO->address = $this->patientData['address'];
        $patientDTO->dateOfBirth = Carbon::parse($this->patientData['date_of_birth']);
        $patientDTO->telpNumber = $this->patientData['telp_number'];
        $patientDTO->history = $this->patientData['history'];
        $patientDTO->medicalRecordFileUrl = $this->patientData['medical_record_file_url'];
        $patientDTO->medicalRecordNumber = $this->patientData['medical_record_number'];
        $insertedPatientDTO = $this->patientRepository->insert($patientDTO);
        $this->assertNotNull($insertedPatientDTO);

        $insertedPatientId = $insertedPatientDTO->id;
        $updatedPatientDTO = new PatientDTO;
        $updatedPatientDTO->name = 'Updated Name';
        $updatedPatientDTO->gender = 'female';
        $updatedPatientDTO->address = 'Updated Address';

        $editedPatientDTO = $this->patientRepository->edit($insertedPatientId, $updatedPatientDTO);
        $this->assertNotNull($editedPatientDTO);
        $this->assertEquals('Updated Name', $editedPatientDTO->name);
        $this->assertEquals('female', $editedPatientDTO->gender);
        $this->assertEquals('Updated Address', $editedPatientDTO->address);

        $this->assertInstanceOf(PatientDTO::class, $editedPatientDTO);
    }

    // test untuk edit patient yang tidak ada
    public function test_edit_non_existent_patient(): void
    {
        $nonExistentPatientId = 9999; // ID yang tidak ada di database
        $updatedPatientDTO = new PatientDTO;
        $updatedPatientDTO->name = 'Updated Name';
        $updatedPatientDTO->gender = 'female';
        $updatedPatientDTO->address = 'Updated Address';
        $editedPatientDTO = $this->patientRepository->edit($nonExistentPatientId, $updatedPatientDTO);
        $this->assertNull($editedPatientDTO);
    }

    // test untuk edit patient dengan data kosong
    public function test_edit_patient_with_empty_data(): void
    {
        $patientDTO = new PatientDTO;
        $patientDTO->name = $this->patientData['fullname'];
        $patientDTO->gender = $this->patientData['gender'];
        $patientDTO->address = $this->patientData['address'];
        $patientDTO->dateOfBirth = Carbon::parse($this->patientData['date_of_birth']);
        $patientDTO->telpNumber = $this->patientData['telp_number'];
        $patientDTO->history = $this->patientData['history'];
        $patientDTO->medicalRecordFileUrl = $this->patientData['medical_record_file_url'];
        $patientDTO->medicalRecordNumber = $this->patientData['medical_record_number'];
        $insertedPatientDTO = $this->patientRepository->insert($patientDTO);
        $this->assertNotNull($insertedPatientDTO);

        $insertedPatientId = $insertedPatientDTO->id;
        $updatedPatientDTO = new PatientDTO; // Data kosong
        $editedPatientDTO = $this->patientRepository->edit($insertedPatientId, $updatedPatientDTO);
        $this->assertNull($editedPatientDTO);
    }

    // test getById patient
    public function test_get_by_id_patient(): void
    {
        $patientDTO = new PatientDTO;
        $patientDTO->name = $this->patientData['fullname'];
        $patientDTO->gender = $this->patientData['gender'];
        $patientDTO->address = $this->patientData['address'];
        $patientDTO->dateOfBirth = Carbon::parse($this->patientData['date_of_birth']);
        $patientDTO->telpNumber = $this->patientData['telp_number'];
        $patientDTO->history = $this->patientData['history'];
        $patientDTO->medicalRecordFileUrl = $this->patientData['medical_record_file_url'];
        $patientDTO->medicalRecordNumber = $this->patientData['medical_record_number'];
        $insertedPatientDTO = $this->patientRepository->insert($patientDTO);
        $this->assertNotNull($insertedPatientDTO);
        $insertedPatientId = $insertedPatientDTO->id;
        $fetchedPatientDTO = $this->patientRepository->getById($insertedPatientId);
        $this->assertNotNull($fetchedPatientDTO);
        $this->assertEquals($insertedPatientDTO->name, $fetchedPatientDTO->name);
        $this->assertEquals($insertedPatientDTO->gender, $fetchedPatientDTO->gender);
        $this->assertInstanceOf(PatientDTO::class, $fetchedPatientDTO);

    }

    // test getById patient yang tidak ada
    public function test_get_by_id_non_existent_patient(): void
    {
        $nonExistentPatientId = 9999; // ID yang tidak ada di database
        $fetchedPatientDTO = $this->patientRepository->getById($nonExistentPatientId);
        $this->assertNull($fetchedPatientDTO);
    }

    // test getLatestId patient
    public function test_get_latest_id_patient(): void
    {
        // Insert some patients first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $patientDTO = new PatientDTO;
            $patientDTO->name = "Patient $i";
            $patientDTO->gender = ($i % 2 == 0) ? 'female' : 'male';
            $patientDTO->address = "$i Main St";
            $patientDTO->dateOfBirth = Carbon::parse("1990-01-0$i");
            $patientDTO->telpNumber = "123456789$i";
            $patientDTO->history = "History $i";
            $patientDTO->medicalRecordFileUrl = "http://example.com/record$i.pdf";
            $patientDTO->medicalRecordNumber = "MRN12345$i";
            $insertedPatientDTO = $this->patientRepository->insert($patientDTO);
            $latestInsertedId = $insertedPatientDTO->id;
        }

        $latestId = $this->patientRepository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId patient ketika tidak ada data
    public function test_get_latest_id_patient_when_no_data(): void
    {
        $latestId = $this->patientRepository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete patient
    public function test_delete_patient(): void
    {
        $patientDTO = new PatientDTO;
        $patientDTO->name = $this->patientData['fullname'];
        $patientDTO->gender = $this->patientData['gender'];
        $patientDTO->address = $this->patientData['address'];
        $patientDTO->dateOfBirth = Carbon::parse($this->patientData['date_of_birth']);
        $patientDTO->telpNumber = $this->patientData['telp_number'];
        $patientDTO->history = $this->patientData['history'];
        $patientDTO->medicalRecordFileUrl = $this->patientData['medical_record_file_url'];
        $patientDTO->medicalRecordNumber = $this->patientData['medical_record_number'];
        $insertedPatientDTO = $this->patientRepository->insert($patientDTO);
        $this->assertNotNull($insertedPatientDTO);
        $insertedPatientId = $insertedPatientDTO->id;
        $deleteResult = $this->patientRepository->delete($insertedPatientId);
        $this->assertTrue($deleteResult);

    }

    // test delete patient yang tidak ada
    public function test_delete_non_existent_patient(): void
    {
        $nonExistentPatientId = 9999; // ID yang tidak ada di database
        $deleteResult = $this->patientRepository->delete($nonExistentPatientId);
        $this->assertFalse($deleteResult);
    }
}
