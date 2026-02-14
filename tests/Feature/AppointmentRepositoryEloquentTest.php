<?php

namespace Tests\Feature;

use App\DTO\DiagnosisDTO;
use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use App\DTO\TreatmentDTO;
use App\DTO\UserDTO;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\DiagnosisRepositoryEloquent;
use App\Repository\PatientRepositoryEloquent;
use App\Repository\RegistrationRepositoryEloquent;
use App\Repository\TreatmentRepositoryEloquent;
use App\Repository\UserRepositoryEloquent;
use App\Type\UserRole;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentRepositoryEloquentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected RegistrationRepositoryEloquent $registrationRepository;

    protected UserRepositoryEloquent $userRepository;

    protected PatientRepositoryEloquent $patientRepository;

    protected DiagnosisRepositoryEloquent $diagnosisRepository;

    protected TreatmentRepositoryEloquent $treatmentRepository;

    protected AppointmentRepositoryEloquent $appointmentRepository;

    protected PatientDTO $patientDTO;

    protected UserDTO $doctorDTO;

    protected DiagnosisDTO $diagnosisDTO;

    protected TreatmentDTO $treatmentDTO;

    // Multiple diagnoses and treatments
    protected array $diagnosisDTOs = [];

    protected array $treatmentDTOs = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationRepository = new RegistrationRepositoryEloquent;
        $this->userRepository = new UserRepositoryEloquent;
        $this->patientRepository = new PatientRepositoryEloquent;
        $this->diagnosisRepository = new DiagnosisRepositoryEloquent;
        $this->treatmentRepository = new TreatmentRepositoryEloquent;
        $this->appointmentRepository = new AppointmentRepositoryEloquent;

        $patientDTO = new PatientDTO;
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
        $doctorDTO = new UserDTO;
        $doctorDTO->username = 'doctor1';
        $doctorDTO->fullname = 'Dr. Jane Smith';
        $doctorDTO->email = 'doctor@example.com';
        $doctorDTO->password = bcrypt('password');
        $doctorDTO->role = UserRole::DOCTOR;

        // Insert akan return UserDTO lengkap dengan id, createdAt, updatedAt
        $this->doctorDTO = $this->userRepository->insert($doctorDTO);

        // insert diagnosis data (single)
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->diseaseName = 'Common Cold';
        $diagnosisDTO->icdCode = 'J00';

        $this->diagnosisDTO = $this->diagnosisRepository->insert($diagnosisDTO);

        // insert treatment data (single)
        $treatmentDTO = new TreatmentDTO;
        $treatmentDTO->treatmentName = 'Rest and Medication';
        $treatmentDTO->treatmentCode = 'hidup jkw';

        $this->treatmentDTO = $this->treatmentRepository->insert($treatmentDTO);

        // Insert 5 diagnoses untuk testing attachMany
        $diagnosesData = [
            ['diseaseName' => 'Influenza', 'icdCode' => 'J11'],
            ['diseaseName' => 'Acute Bronchitis', 'icdCode' => 'J20'],
            ['diseaseName' => 'Pneumonia', 'icdCode' => 'J18'],
            ['diseaseName' => 'Asthma', 'icdCode' => 'J45'],
            ['diseaseName' => 'Hypertension', 'icdCode' => 'I10'],
        ];

        foreach ($diagnosesData as $data) {
            $diagnosisDTO = new DiagnosisDTO;
            $diagnosisDTO->diseaseName = $data['diseaseName'];
            $diagnosisDTO->icdCode = $data['icdCode'];
            $this->diagnosisDTOs[] = $this->diagnosisRepository->insert($diagnosisDTO);
        }

        // Insert 5 treatments untuk testing attachMany
        $treatmentsData = [
            ['treatmentName' => 'Physical Therapy', 'treatmentCode' => 'PT001'],
            ['treatmentName' => 'Antibiotic Treatment', 'treatmentCode' => 'AT002'],
            ['treatmentName' => 'Pain Management', 'treatmentCode' => 'PM003'],
            ['treatmentName' => 'Respiratory Therapy', 'treatmentCode' => 'RT004'],
            ['treatmentName' => 'Blood Pressure Management', 'treatmentCode' => 'BPM005'],
        ];

        foreach ($treatmentsData as $data) {
            $treatmentDTO = new TreatmentDTO;
            $treatmentDTO->treatmentName = $data['treatmentName'];
            $treatmentDTO->treatmentCode = $data['treatmentCode'];
            $this->treatmentDTOs[] = $this->treatmentRepository->insert($treatmentDTO);
        }
    }

    // test untuk attach diagnosis ke registration
    public function test_attach_diagnosis_to_registration_success(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);
        $canAttachment = $this->appointmentRepository->attachDiagnosisToRegistration($insertedRegistrationDTO, $this->diagnosisDTO);

        $this->assertEquals(true, $canAttachment);
    }

    // test untuk attach lagi (tadi sudah attach) diagnosis ke registration
    public function test_attach_diagnosis_to_registration_failed_when_diagnosis_id_already_exist(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        $canAttachment = $this->appointmentRepository->attachDiagnosisToRegistration($insertedRegistrationDTO, $this->diagnosisDTO);

        $canAttachmentAgain = $this->appointmentRepository->attachDiagnosisToRegistration($insertedRegistrationDTO, $this->diagnosisDTO);

        $this->assertEquals(false, $canAttachmentAgain);
    }

    // test untuk attach treatment ke registration
    public function test_attach_treatment_to_registration_success(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);
        $canAttachment = $this->appointmentRepository->attachTreatmentToRegistration($insertedRegistrationDTO, $this->treatmentDTO);

        $this->assertEquals(true, $canAttachment);
    }

    // test untuk attach lagi (tadi sudah attach) treatment ke registration
    public function test_attach_treatment_to_registration_failed_when_treatment_id_already_exist(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        $canAttachment = $this->appointmentRepository->attachTreatmentToRegistration($insertedRegistrationDTO, $this->treatmentDTO);

        $canAttachmentAgain = $this->appointmentRepository->attachTreatmentToRegistration($insertedRegistrationDTO, $this->treatmentDTO);

        $this->assertEquals(false, $canAttachmentAgain);
    }

    // test untuk attach many diagnosis ke registration
    public function test_attach_many_diagnosis_to_registration_success(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        // Attach multiple diagnoses sekaligus
        $canAttachment = $this->appointmentRepository->attachManyDiagnosesToRegistration(
            $insertedRegistrationDTO,
            $this->diagnosisDTOs
        );

        $this->assertEquals(true, $canAttachment);
    }

    // test untuk attach lagi (tadi sudah attach) many diagnosis ke registration
    public function test_attach_many_diagnosis_to_registration_failed_when_diagnosis_id_already_exist(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        // Attach pertama kali
        $canAttachment = $this->appointmentRepository->attachManyDiagnosesToRegistration(
            $insertedRegistrationDTO,
            $this->diagnosisDTOs
        );
        $this->assertEquals(true, $canAttachment);

        // Attach lagi dengan data yang sama (should still return true karena syncWithoutDetaching)
        // Tapi kita bisa cek dengan attach satu diagnosis yang sudah ada
        $canAttachmentAgain = $this->appointmentRepository->attachDiagnosisToRegistration(
            $insertedRegistrationDTO,
            $this->diagnosisDTOs[0]
        );

        $this->assertEquals(false, $canAttachmentAgain);
    }

    // test untuk attach many treatment ke registration
    public function test_attach_many_treatment_to_registration_success(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        // Attach multiple treatments sekaligus
        $canAttachment = $this->appointmentRepository->attachManyTreatmentsToRegistration(
            $insertedRegistrationDTO,
            $this->treatmentDTOs
        );

        $this->assertEquals(true, $canAttachment);
    }

    // test untuk attach lagi (tadi sudah attach) many treatment ke registration
    public function test_attach_many_treatment_to_registration_failed_when_treatment_id_already_exist(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        // Attach pertama kali
        $canAttachment = $this->appointmentRepository->attachManyTreatmentsToRegistration(
            $insertedRegistrationDTO,
            $this->treatmentDTOs
        );
        $this->assertEquals(true, $canAttachment);

        // Attach lagi dengan satu treatment yang sudah ada (should return false)
        $canAttachmentAgain = $this->appointmentRepository->attachTreatmentToRegistration(
            $insertedRegistrationDTO,
            $this->treatmentDTOs[0]
        );

        $this->assertEquals(false, $canAttachmentAgain);
    }

    // test untuk attach array id kosong
    public function test_attach_many_diagnoses_to_registration_with_empty_array(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        // Attach dengan array kosong
        $canAttachment = $this->appointmentRepository->attachManyDiagnosesToRegistration(
            $insertedRegistrationDTO,
            []
        );

        $this->assertEquals(false, $canAttachment);
    }

    public function test_attach_many_treatment_to_registration_with_empty_array(): void
    {
        $registrationDTO = new RegistrationDTO;
        $registrationDTO->patient = $this->patientDTO;
        $registrationDTO->doctor = $this->doctorDTO;
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);

        $this->assertNotNull($insertedRegistrationDTO);

        // Attach dengan array kosong
        $canAttachment = $this->appointmentRepository->attachManyTreatmentsToRegistration(
            $insertedRegistrationDTO,
            []
        );

        $this->assertEquals(false, $canAttachment);
    }
}
