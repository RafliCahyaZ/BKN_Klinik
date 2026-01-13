<?php

namespace Tests\Feature;

use App\DTO\TreatmentDTO;
use App\Repository\TreatmentRepositoryEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentRepositoryEloquentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected TreatmentRepositoryEloquent $repository;

    protected array $treatmentData = [
        'treatment_code' => 1001,
        'treatment_name' => 'Basic Checkup',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TreatmentRepositoryEloquent();

        $this->treatmentData = [
            'treatment_code' => 1001,
            'treatment_name' => 'Basic Checkup',
        ];
    }

    // test untuk insert treatment
    public function testInsertTreatmentSuccess(): void 
    {
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentCode = $this->treatmentData['treatment_code'];
        $treatmentDTO->treatmentName = $this->treatmentData['treatment_name'];
        
        $insertedTreatmentDTO = $this->repository->insert($treatmentDTO);
        $this->assertNotNull($insertedTreatmentDTO);
        $this->assertEquals($this->treatmentData['treatment_code'], $insertedTreatmentDTO->treatmentCode);
        $this->assertEquals($this->treatmentData['treatment_name'], $insertedTreatmentDTO->treatmentName);

        // cek return value adalah instance of TreatmentDTO
        $this->assertInstanceOf(TreatmentDTO::class, $insertedTreatmentDTO);
    }

    // test untuk insert treatment gagal ketika data unik sudah ada
    public function testInsertTreatmentFailedWhenUniqueDataExists(): void 
    {
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentCode = $this->treatmentData['treatment_code'];
        $treatmentDTO->treatmentName = $this->treatmentData['treatment_name'];
        
        $firstInsert = $this->repository->insert($treatmentDTO);
        $this->assertNotNull($firstInsert);

        $duplicateTreatmentDTO = new TreatmentDTO();
        $duplicateTreatmentDTO->treatmentCode = $this->treatmentData['treatment_code']; // Same code as first treatment
        $duplicateTreatmentDTO->treatmentName = 'Different Name';
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        $secondInsert = $this->repository->insert($duplicateTreatmentDTO);
        $this->assertEquals(null, $secondInsert);
    }
    

    // test untuk get all treatments
    public function testGetAllTreatments(): void 
    {
        // Insert some treatments first
        for ($i = 1; $i <= 3; $i++) {
            $treatmentDTO = new TreatmentDTO();
            $treatmentDTO->treatmentCode = 1000 + $i;
            $treatmentDTO->treatmentName = "Treatment $i";

            $this->repository->insert($treatmentDTO); 
        }

        $treatments = $this->repository->getAll();
        // cek treatments adalah array
        $this->assertIsArray($treatments);
        $this->assertCount(3, $treatments);
    }


    // test untuk getall treatments ketika tidak ada data
    public function testGetAllTreatmentsWhenNoData(): void 
    {
        $treatments = $this->repository->getAll();
        // cek treatments adalah array kosong
        $this->assertIsArray($treatments);
        $this->assertCount(0, $treatments);
    }


    // test untuk edit treatment
    public function testEditTreatment(): void 
    {
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentCode = $this->treatmentData['treatment_code'];
        $treatmentDTO->treatmentName = $this->treatmentData['treatment_name'];
        
        $insertedTreatmentDTO = $this->repository->insert($treatmentDTO);    
        $this->assertNotNull($insertedTreatmentDTO);

        $insertedTreatmentId = $insertedTreatmentDTO->id;
        $updatedTreatmentDTO = new TreatmentDTO();
        $updatedTreatmentDTO->treatmentCode = 1002;
        $updatedTreatmentDTO->treatmentName = "Updated Treatment";
        
        $editedTreatmentDTO = $this->repository->edit($insertedTreatmentId, $updatedTreatmentDTO);
        $this->assertNotNull($editedTreatmentDTO);
        $this->assertEquals(1002, $editedTreatmentDTO->treatmentCode);
        $this->assertEquals("Updated Treatment", $editedTreatmentDTO->treatmentName);

        $this->assertInstanceOf(TreatmentDTO::class, $editedTreatmentDTO);
    }   


    // test untuk edit treatment yang tidak ada
    public function testEditNonExistentTreatment(): void 
    {
        $nonExistentTreatmentId = 9999; // ID yang tidak ada di database
        $updatedTreatmentDTO = new TreatmentDTO();
        $updatedTreatmentDTO->treatmentCode = 1002;
        $updatedTreatmentDTO->treatmentName = "Updated Treatment";
        
        $editedTreatmentDTO = $this->repository->edit($nonExistentTreatmentId, $updatedTreatmentDTO);
        $this->assertNull($editedTreatmentDTO);
    }

    // test untuk edit treatment dengan data kosong
    public function testEditTreatmentWithEmptyData(): void 
    {
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentCode = $this->treatmentData['treatment_code'];
        $treatmentDTO->treatmentName = $this->treatmentData['treatment_name'];
        
        $insertedTreatmentDTO = $this->repository->insert($treatmentDTO);    
        $this->assertNotNull($insertedTreatmentDTO);

        $insertedTreatmentId = $insertedTreatmentDTO->id;
        $updatedTreatmentDTO = new TreatmentDTO(); // Data kosong
        $editedTreatmentDTO = $this->repository->edit($insertedTreatmentId, $updatedTreatmentDTO);
        $this->assertNull($editedTreatmentDTO);
    }


    // test getById treatment
    public function testGetByIdTreatment(): void 
    {
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentCode = $this->treatmentData['treatment_code'];
        $treatmentDTO->treatmentName = $this->treatmentData['treatment_name'];
        
        $insertedTreatmentDTO = $this->repository->insert($treatmentDTO);    
        $this->assertNotNull($insertedTreatmentDTO);
        $insertedTreatmentId = $insertedTreatmentDTO->id;
        $fetchedTreatmentDTO = $this->repository->getById($insertedTreatmentId);
        $this->assertNotNull($fetchedTreatmentDTO);
        $this->assertEquals($insertedTreatmentDTO->treatmentCode, $fetchedTreatmentDTO->treatmentCode);
        $this->assertEquals($insertedTreatmentDTO->treatmentName, $fetchedTreatmentDTO->treatmentName);  
        $this->assertInstanceOf(TreatmentDTO::class, $fetchedTreatmentDTO);
        
    }

    // test getById treatment yang tidak ada
    public function testGetByIdNonExistentTreatment(): void 
    {
        $nonExistentTreatmentId = 9999; // ID yang tidak ada di database
        $fetchedTreatmentDTO = $this->repository->getById($nonExistentTreatmentId);
        $this->assertNull($fetchedTreatmentDTO);
    }

    // test getLatestId treatment
    public function testGetLatestIdTreatment(): void 
    {
        // Insert some treatments first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $treatmentDTO = new TreatmentDTO();
            $treatmentDTO->treatmentCode = 1000 + $i;
            $treatmentDTO->treatmentName = "Treatment $i";
            
            $insertedTreatmentDTO = $this->repository->insert($treatmentDTO);
            $latestInsertedId = $insertedTreatmentDTO->id;
        }

        $latestId = $this->repository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId treatment ketika tidak ada data
    public function testGetLatestIdTreatmentWhenNoData(): void 
    {
        $latestId = $this->repository->getLatestId();
        $this->assertEquals(0, $latestId);
    }


    // test delete treatment
    public function testDeleteTreatment(): void 
    {
        $treatmentDTO = new TreatmentDTO();
        $treatmentDTO->treatmentCode = $this->treatmentData['treatment_code'];
        $treatmentDTO->treatmentName = $this->treatmentData['treatment_name'];
        
        $insertedTreatmentDTO = $this->repository->insert($treatmentDTO);    
        $this->assertNotNull($insertedTreatmentDTO);
        $insertedTreatmentId = $insertedTreatmentDTO->id;
        $deleteResult = $this->repository->delete($insertedTreatmentId);
        $this->assertTrue($deleteResult);

    }

    // test delete treatment yang tidak ada
    public function testDeleteNonExistentTreatment(): void 
    {
        $nonExistentTreatmentId = 9999; // ID yang tidak ada di database
        $deleteResult = $this->repository->delete($nonExistentTreatmentId);
        $this->assertFalse($deleteResult); 
    }

}
