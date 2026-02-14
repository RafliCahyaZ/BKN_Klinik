<?php

namespace Tests\Feature;

use App\DTO\DiagnosisDTO;
use App\Repository\DiagnosisRepositoryEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiagnosisRepositoryEloquentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected DiagnosisRepositoryEloquent $repository;

    protected array $diagnosis = [
        'icd_code' => 'A00',
        'disease_name' => 'Cholera',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new DiagnosisRepositoryEloquent;

        $this->diagnosis = [
            'icd_code' => 'A00',
            'disease_name' => 'Cholera',
        ];
    }

    // test untuk insert diagonisis sukses
    public function test_insert_diagnosis_success(): void
    {
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->icdCode = $this->diagnosis['icd_code'];
        $diagnosisDTO->diseaseName = $this->diagnosis['disease_name'];

        $insertedDiagnosisDTO = $this->repository->insert($diagnosisDTO);
        $this->assertNotNull($insertedDiagnosisDTO);
        $this->assertEquals($this->diagnosis['icd_code'], $insertedDiagnosisDTO->icdCode);
        $this->assertEquals($this->diagnosis['disease_name'], $insertedDiagnosisDTO->diseaseName);

        // cek return value adalah instance of DiagnosisDTO
        $this->assertInstanceOf(DiagnosisDTO::class, $insertedDiagnosisDTO);
    }

    // test untuk insert diagnosis gagal ketika data unik sudah ada
    public function test_insert_diagnosis_failed_when_unique_data_exists(): void
    {
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->icdCode = $this->diagnosis['icd_code'];
        $diagnosisDTO->diseaseName = $this->diagnosis['disease_name'];

        $firstInsert = $this->repository->insert($diagnosisDTO);
        $this->assertNotNull($firstInsert);

        $duplicateDiagnosisDTO = new DiagnosisDTO;
        $duplicateDiagnosisDTO->icdCode = $this->diagnosis['icd_code']; // Same ICD code
        $duplicateDiagnosisDTO->diseaseName = 'Different Disease Name';

        $this->expectException(\Illuminate\Database\QueryException::class);
        $secondInsert = $this->repository->insert($duplicateDiagnosisDTO);
        $this->assertEquals(null, $secondInsert);
    }

    // test untuk get all diagnosis
    public function test_get_all_diagnosis(): void
    {
        // Insert some diagnosis first
        $diagnosisData = [
            ['icd_code' => 'A01', 'disease_name' => 'Typhoid fever'],
            ['icd_code' => 'A02', 'disease_name' => 'Paratyphoid fever'],
            ['icd_code' => 'A03', 'disease_name' => 'Shigellosis'],
        ];

        for ($i = 0; $i < 3; $i++) {
            $diagnosisDTO = new DiagnosisDTO;
            $diagnosisDTO->icdCode = $diagnosisData[$i]['icd_code'];
            $diagnosisDTO->diseaseName = $diagnosisData[$i]['disease_name'];

            $this->repository->insert($diagnosisDTO);
        }

        $diagnoses = $this->repository->getAll();
        // cek diagnoses adalah array
        $this->assertIsArray($diagnoses);
        $this->assertCount(3, $diagnoses);
    }

    // test untuk getall diagnosis ketika tidak ada data
    public function test_get_all_diagnosis_when_no_data(): void
    {
        $diagnoses = $this->repository->getAll();
        // cek diagnoses adalah array kosong
        $this->assertIsArray($diagnoses);
        $this->assertCount(0, $diagnoses);
    }

    // test untuk edit diagnosis
    public function test_edit_diagnosis(): void
    {
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->icdCode = $this->diagnosis['icd_code'];
        $diagnosisDTO->diseaseName = $this->diagnosis['disease_name'];

        $insertedDiagnosisDTO = $this->repository->insert($diagnosisDTO);
        $this->assertNotNull($insertedDiagnosisDTO);

        $insertedDiagnosisId = $insertedDiagnosisDTO->id;
        $updatedDiagnosisDTO = new DiagnosisDTO;
        $updatedDiagnosisDTO->icdCode = 'A01';
        $updatedDiagnosisDTO->diseaseName = 'Updated Disease Name';

        $editedDiagnosisDTO = $this->repository->edit($insertedDiagnosisId, $updatedDiagnosisDTO);
        $this->assertNotNull($editedDiagnosisDTO);
        $this->assertEquals('A01', $editedDiagnosisDTO->icdCode);
        $this->assertEquals('Updated Disease Name', $editedDiagnosisDTO->diseaseName);

        $this->assertInstanceOf(DiagnosisDTO::class, $editedDiagnosisDTO);
    }

    // test untuk edit diagnosis yang tidak ada
    public function test_edit_non_existent_diagnosis(): void
    {
        $nonExistentDiagnosisId = 9999; // ID yang tidak ada di database
        $updatedDiagnosisDTO = new DiagnosisDTO;
        $updatedDiagnosisDTO->icdCode = 'A01';
        $updatedDiagnosisDTO->diseaseName = 'Updated Disease Name';

        $editedDiagnosisDTO = $this->repository->edit($nonExistentDiagnosisId, $updatedDiagnosisDTO);
        $this->assertNull($editedDiagnosisDTO);
    }

    // test untuk edit diagnosis dengan data kosong
    public function test_edit_diagnosis_with_empty_data(): void
    {
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->icdCode = $this->diagnosis['icd_code'];
        $diagnosisDTO->diseaseName = $this->diagnosis['disease_name'];

        $insertedDiagnosisDTO = $this->repository->insert($diagnosisDTO);
        $this->assertNotNull($insertedDiagnosisDTO);

        $insertedDiagnosisId = $insertedDiagnosisDTO->id;
        $updatedDiagnosisDTO = new DiagnosisDTO; // Data kosong

        $editedDiagnosisDTO = $this->repository->edit($insertedDiagnosisId, $updatedDiagnosisDTO);
        $this->assertNull($editedDiagnosisDTO);
    }

    // test getById diagnosis
    public function test_get_by_id_diagnosis(): void
    {
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->icdCode = $this->diagnosis['icd_code'];
        $diagnosisDTO->diseaseName = $this->diagnosis['disease_name'];

        $insertedDiagnosisDTO = $this->repository->insert($diagnosisDTO);
        $this->assertNotNull($insertedDiagnosisDTO);

        $insertedDiagnosisId = $insertedDiagnosisDTO->id;
        $fetchedDiagnosisDTO = $this->repository->getById($insertedDiagnosisId);

        $this->assertNotNull($fetchedDiagnosisDTO);
        $this->assertEquals($insertedDiagnosisDTO->icdCode, $fetchedDiagnosisDTO->icdCode);
        $this->assertEquals($insertedDiagnosisDTO->diseaseName, $fetchedDiagnosisDTO->diseaseName);
        $this->assertInstanceOf(DiagnosisDTO::class, $fetchedDiagnosisDTO);
    }

    // test getById diagnosis yang tidak ada
    public function test_get_by_id_non_existent_diagnosis(): void
    {
        $nonExistentDiagnosisId = 9999; // ID yang tidak ada di database
        $fetchedDiagnosisDTO = $this->repository->getById($nonExistentDiagnosisId);
        $this->assertNull($fetchedDiagnosisDTO);
    }

    // test getLatestId diagnosis
    public function test_get_latest_id_diagnosis(): void
    {
        // Insert some diagnoses first
        $diagnosisData = [
            ['icd_code' => 'A01', 'disease_name' => 'Typhoid fever'],
            ['icd_code' => 'A02', 'disease_name' => 'Paratyphoid fever'],
            ['icd_code' => 'A03', 'disease_name' => 'Shigellosis'],
            ['icd_code' => 'A04', 'disease_name' => 'Other bacterial intestinal infections'],
        ];

        $latestInsertedId = null;
        for ($i = 0; $i < 4; $i++) {
            $diagnosisDTO = new DiagnosisDTO;
            $diagnosisDTO->icdCode = $diagnosisData[$i]['icd_code'];
            $diagnosisDTO->diseaseName = $diagnosisData[$i]['disease_name'];

            $insertedDiagnosisDTO = $this->repository->insert($diagnosisDTO);
            $latestInsertedId = $insertedDiagnosisDTO->id;
        }

        $latestId = $this->repository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId diagnosis ketika tidak ada data
    public function test_get_latest_id_diagnosis_when_no_data(): void
    {
        $latestId = $this->repository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete diagnosis
    public function test_delete_diagnosis(): void
    {
        $diagnosisDTO = new DiagnosisDTO;
        $diagnosisDTO->icdCode = $this->diagnosis['icd_code'];
        $diagnosisDTO->diseaseName = $this->diagnosis['disease_name'];

        $insertedDiagnosisDTO = $this->repository->insert($diagnosisDTO);
        $this->assertNotNull($insertedDiagnosisDTO);

        $insertedDiagnosisId = $insertedDiagnosisDTO->id;
        $deleteResult = $this->repository->delete($insertedDiagnosisId);
        $this->assertTrue($deleteResult);
    }

    // test delete diagnosis yang tidak ada
    public function test_delete_non_existent_diagnosis(): void
    {
        $nonExistentDiagnosisId = 9999; // ID yang tidak ada di database
        $deleteResult = $this->repository->delete($nonExistentDiagnosisId);
        $this->assertFalse($deleteResult);
    }
}
