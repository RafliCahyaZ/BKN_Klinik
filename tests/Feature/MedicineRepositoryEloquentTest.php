<?php

namespace Tests\Feature;

use App\DTO\MedicineDTO;
use App\DTO\UnitDTO;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\UnitRepositoryEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected MedicineRepositoryEloquent $medicineRepository;

    protected UnitRepositoryEloquent $unitRepository;

    protected UnitDTO $unitDTO;

    protected array $medicineData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->medicineRepository = new MedicineRepositoryEloquent;
        $this->unitRepository = new UnitRepositoryEloquent;

        $unitDTO = new UnitDTO;
        $unitDTO->name = 'Tablet';
        $this->unitDTO = $this->unitRepository->insert($unitDTO);

        $this->medicineData = [
            'name' => 'Paracetamol',
            'stock' => 100,
            'unit' => $this->unitDTO,
        ];
    }

    // test untuk insert medicine
    public function test_insert_medicine_success(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = $this->medicineData['name'];
        $medicineDTO->stock = $this->medicineData['stock'];
        $medicineDTO->unit = $this->medicineData['unit'];

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);

        $this->assertNotNull($insertedMedicineDTO);
        $this->assertEquals($this->medicineData['name'], $insertedMedicineDTO->name);
        $this->assertEquals($this->medicineData['stock'], $insertedMedicineDTO->stock);
        $this->assertEquals($this->medicineData['unit']->id, $insertedMedicineDTO->unit->id);
        $this->assertInstanceOf(MedicineDTO::class, $insertedMedicineDTO);
    }

    // test untuk insert medicine gagal ketika unit tidak ada
    public function test_insert_medicine_failed_when_unit_not_exists(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = 'Invalid Medicine';
        $medicineDTO->stock = 50;

        // Create invalid unit DTO
        $invalidUnitDTO = new UnitDTO;
        $invalidUnitDTO->id = '9999'; // Non-existent unit ID
        $medicineDTO->unit = $invalidUnitDTO;

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);

        $this->assertNull($insertedMedicineDTO);
    }

    // test untuk get all medicines
    public function test_get_all_medicines(): void
    {
        // Insert some medicines first
        for ($i = 1; $i <= 3; $i++) {
            $medicineDTO = new MedicineDTO;
            $medicineDTO->name = "Medicine $i";
            $medicineDTO->stock = 50 * $i;
            $medicineDTO->unit = $this->unitDTO;

            $this->medicineRepository->insert($medicineDTO);
        }

        $medicines = $this->medicineRepository->getAll();
        $this->assertIsArray($medicines);
        $this->assertCount(3, $medicines);
        $this->assertInstanceOf(MedicineDTO::class, $medicines[0]);
    }

    // test untuk getall medicines ketika tidak ada data
    public function test_get_all_medicines_when_no_data(): void
    {
        $medicines = $this->medicineRepository->getAll();
        $this->assertIsArray($medicines);
        $this->assertCount(0, $medicines);
    }

    // test untuk edit medicine
    public function test_edit_medicine(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = $this->medicineData['name'];
        $medicineDTO->stock = $this->medicineData['stock'];
        $medicineDTO->unit = $this->medicineData['unit'];

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);
        $this->assertNotNull($insertedMedicineDTO);

        $insertedMedicineId = $insertedMedicineDTO->id;
        $updatedMedicineDTO = new MedicineDTO;
        $updatedMedicineDTO->name = 'Updated Medicine Name';
        $updatedMedicineDTO->stock = 200;

        $editedMedicineDTO = $this->medicineRepository->edit($insertedMedicineId, $updatedMedicineDTO);
        $this->assertNotNull($editedMedicineDTO);
        $this->assertEquals('Updated Medicine Name', $editedMedicineDTO->name);
        $this->assertEquals(200, $editedMedicineDTO->stock);
        $this->assertInstanceOf(MedicineDTO::class, $editedMedicineDTO);
    }

    // test untuk edit medicine yang tidak ada
    public function test_edit_non_existent_medicine(): void
    {
        $nonExistentMedicineId = '9999';
        $updatedMedicineDTO = new MedicineDTO;
        $updatedMedicineDTO->name = 'Updated Name';
        $updatedMedicineDTO->stock = 100;

        $editedMedicineDTO = $this->medicineRepository->edit($nonExistentMedicineId, $updatedMedicineDTO);
        $this->assertNull($editedMedicineDTO);
    }

    // test untuk edit medicine dengan data kosong
    public function test_edit_medicine_with_empty_data(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = $this->medicineData['name'];
        $medicineDTO->stock = $this->medicineData['stock'];
        $medicineDTO->unit = $this->medicineData['unit'];

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);
        $this->assertNotNull($insertedMedicineDTO);

        $insertedMedicineId = $insertedMedicineDTO->id;
        $updatedMedicineDTO = new MedicineDTO; // Data kosong

        $editedMedicineDTO = $this->medicineRepository->edit($insertedMedicineId, $updatedMedicineDTO);
        $this->assertNull($editedMedicineDTO);
    }

    // test getById medicine
    public function test_get_by_id_medicine(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = $this->medicineData['name'];
        $medicineDTO->stock = $this->medicineData['stock'];
        $medicineDTO->unit = $this->medicineData['unit'];

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);
        $this->assertNotNull($insertedMedicineDTO);

        $insertedMedicineId = $insertedMedicineDTO->id;
        $fetchedMedicineDTO = $this->medicineRepository->getById($insertedMedicineId);

        $this->assertNotNull($fetchedMedicineDTO);
        $this->assertEquals($insertedMedicineDTO->name, $fetchedMedicineDTO->name);
        $this->assertEquals($insertedMedicineDTO->stock, $fetchedMedicineDTO->stock);
        $this->assertEquals($insertedMedicineDTO->unit->id, $fetchedMedicineDTO->unit->id);
        $this->assertInstanceOf(MedicineDTO::class, $fetchedMedicineDTO);
    }

    // test getById medicine yang tidak ada
    public function test_get_by_id_non_existent_medicine(): void
    {
        $nonExistentMedicineId = '9999';
        $fetchedMedicineDTO = $this->medicineRepository->getById($nonExistentMedicineId);
        $this->assertNull($fetchedMedicineDTO);
    }

    // test getLatestId medicine
    public function test_get_latest_id_medicine(): void
    {
        // Insert some medicines first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $medicineDTO = new MedicineDTO;
            $medicineDTO->name = "Medicine $i";
            $medicineDTO->stock = 50 * $i;
            $medicineDTO->unit = $this->unitDTO;

            $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);
            $latestInsertedId = $insertedMedicineDTO->id;
        }

        $latestId = $this->medicineRepository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId medicine ketika tidak ada data
    public function test_get_latest_id_medicine_when_no_data(): void
    {
        $latestId = $this->medicineRepository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete medicine
    public function test_delete_medicine(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = $this->medicineData['name'];
        $medicineDTO->stock = $this->medicineData['stock'];
        $medicineDTO->unit = $this->medicineData['unit'];

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);
        $this->assertNotNull($insertedMedicineDTO);

        $insertedMedicineId = $insertedMedicineDTO->id;
        $deleteResult = $this->medicineRepository->delete($insertedMedicineId);
        $this->assertTrue($deleteResult);

        // Verify medicine is deleted
        $fetchedMedicineDTO = $this->medicineRepository->getById($insertedMedicineId);
        $this->assertNull($fetchedMedicineDTO);
    }

    // test delete medicine yang tidak ada
    public function test_delete_non_existent_medicine(): void
    {
        $nonExistentMedicineId = '9999';
        $deleteResult = $this->medicineRepository->delete($nonExistentMedicineId);
        $this->assertFalse($deleteResult);
    }

    // test update stock medicine
    public function test_update_medicinestock(): void
    {
        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = $this->medicineData['name'];
        $medicineDTO->stock = $this->medicineData['stock'];
        $medicineDTO->unit = $this->medicineData['unit'];

        $insertedMedicineDTO = $this->medicineRepository->insert($medicineDTO);
        $this->assertNotNull($insertedMedicineDTO);
        $this->assertEquals(100, $insertedMedicineDTO->stock);

        // Update only stock
        $updateDTO = new MedicineDTO;
        $updateDTO->stock = 150;

        $editedMedicineDTO = $this->medicineRepository->edit($insertedMedicineDTO->id, $updateDTO);
        $this->assertNotNull($editedMedicineDTO);
        $this->assertEquals(150, $editedMedicineDTO->stock);
        $this->assertEquals($insertedMedicineDTO->name, $editedMedicineDTO->name); // Name should remain same
    }

    // test insert multiple medicines dengan unit yang sama
    public function test_insert_multiple_medicines_with_same_unit(): void
    {
        $medicinesData = [
            ['name' => 'Aspirin', 'stock' => 80],
            ['name' => 'Ibuprofen', 'stock' => 120],
            ['name' => 'Amoxicillin', 'stock' => 60],
        ];

        foreach ($medicinesData as $data) {
            $medicineDTO = new MedicineDTO;
            $medicineDTO->name = $data['name'];
            $medicineDTO->stock = $data['stock'];
            $medicineDTO->unit = $this->unitDTO;

            $insertedMedicine = $this->medicineRepository->insert($medicineDTO);
            $this->assertNotNull($insertedMedicine);
            $this->assertEquals($data['name'], $insertedMedicine->name);
        }

        $allMedicines = $this->medicineRepository->getAll();
        $this->assertCount(3, $allMedicines);
    }

    // test untuk verify unit tersedia
    public function test_unit_is_available(): void
    {
        $this->assertNotNull($this->unitDTO);
        $this->assertEquals('Tablet', $this->unitDTO->name);
    }
}
