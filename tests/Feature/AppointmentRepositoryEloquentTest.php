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
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationRepository = new RegistrationRepositoryEloquent();
        $this->userRepository = new UserRepositoryEloquent();
        $this->patientRepository = new PatientRepositoryEloquent();
        $this->diagnosisRepository = new DiagnosisRepositoryEloquent();
        $this->treatmentRepository = new TreatmentRepositoryEloquent();
        $this->appointmentRepository = new AppointmentRepositoryEloquent();

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
        $doctorDTO->role = 'doctor';

        // Insert akan return UserDTO lengkap dengan id, createdAt, updatedAt
        $this->doctorDTO = $this->userRepository->insert($doctorDTO);


        // insert diagnosis data
        $diagnosisDTO = new DiagnosisDTO();
        $diagnosisDTO->diseaseName = 'Common Cold';
        $diagnosisDTO->icdCode = 'J00';

        $this->diagnosisDTO = $this->diagnosisRepository->insert($diagnosisDTO);

        // insert treatment data
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentName = 'Rest and Medication';
        $treatmentDTO->treatmentCode = 'hidup jkw';

        $this->treatmentDTO = $this->treatmentRepository->insert($treatmentDTO);
    }
    // test untuk attach diagnosis ke registration
    public function testAttachDiagnosisToRegistrationSuccess(): void 
    {
        $registrationDTO = new RegistrationDTO();        
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);
        
        $this->assertNotNull($insertedRegistrationDTO);
        $canAttachment = $this->appointmentRepository->attachDiagnosisToRegistration($insertedRegistrationDTO, $this->diagnosisDTO);

        $this->assertEquals(true, $canAttachment);
    }

    // test untuk attach lagi (tadi sudah attach) diagnosis ke registration
    public function testAttachDiagnosisToRegistrationFailedWhenDiagnosisIDAlreadyExist(): void 
    {
        $registrationDTO = new RegistrationDTO();        
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
    public function testAttachTreatmentToRegistrationSuccess(): void 
    {
        $registrationDTO = new RegistrationDTO();        
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);
        
        $this->assertNotNull($insertedRegistrationDTO);
        $canAttachment = $this->appointmentRepository->attachTreatmentToRegistration($insertedRegistrationDTO, $this->treatmentDTO);

        $this->assertEquals(true, $canAttachment);
    }

    // test untuk attach lagi (tadi sudah attach) treatment ke registration
    public function testAttachTreatmentToRegistrationFailedWhenTreatmentIDAlreadyExist(): void 
    {
        $registrationDTO = new RegistrationDTO();        
        $registrationDTO->patient = $this->patientDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->doctor = $this->doctorDTO; // Langsung gunakan DTO dari setUp
        $registrationDTO->complaint = 'Sakit kepala dan demam';

        $insertedRegistrationDTO = $this->registrationRepository->insert($registrationDTO);
        
        $this->assertNotNull($insertedRegistrationDTO);

        $canAttachment = $this->appointmentRepository->attachTreatmentToRegistration($insertedRegistrationDTO, $this->treatmentDTO);

        $canAttachmentAgain = $this->appointmentRepository->attachTreatmentToRegistration($insertedRegistrationDTO, $this->treatmentDTO);

        $this->assertEquals(false, $canAttachmentAgain);
    }
}
