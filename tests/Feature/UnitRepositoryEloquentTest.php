<?php

namespace Tests\Feature;

use App\DTO\UnitDTO;
use App\Repository\UnitRepositoryEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected UnitRepositoryEloquent $unitRepository;

    protected array $unitData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unitRepository = new UnitRepositoryEloquent;

        $this->unitData = [
            'name' => 'Tablet',
        ];
    }

    // test untuk insert unit
    public function test_insert_unit_success(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = $this->unitData['name'];

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);

        $this->assertNotNull($insertedUnitDTO);
        $this->assertEquals($this->unitData['name'], $insertedUnitDTO->name);
        $this->assertInstanceOf(UnitDTO::class, $insertedUnitDTO);
        $this->assertNotNull($insertedUnitDTO->id);
        $this->assertNotNull($insertedUnitDTO->createdAt);
        $this->assertNotNull($insertedUnitDTO->updatedAt);
    }

    // test untuk get all units
    public function test_get_all_units(): void
    {
        // Insert some units first
        $unitNames = ['Tablet', 'Capsule', 'Sirup', 'Ampul', 'Botol'];

        foreach ($unitNames as $name) {
            $unitDTO = new UnitDTO;
            $unitDTO->name = $name;
            $this->unitRepository->insert($unitDTO);
        }

        $units = $this->unitRepository->getAll();
        $this->assertIsArray($units);
        $this->assertCount(5, $units);
        $this->assertInstanceOf(UnitDTO::class, $units[0]);
    }

    // test untuk getall units ketika tidak ada data
    public function test_get_all_units_when_no_data(): void
    {
        $units = $this->unitRepository->getAll();
        $this->assertIsArray($units);
        $this->assertCount(0, $units);
    }

    // test untuk edit unit
    public function test_edit_unit(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = $this->unitData['name'];

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);
        $this->assertNotNull($insertedUnitDTO);

        $insertedUnitId = $insertedUnitDTO->id;
        $updatedUnitDTO = new UnitDTO;
        $updatedUnitDTO->name = 'Updated Unit Name';

        $editedUnitDTO = $this->unitRepository->edit($insertedUnitId, $updatedUnitDTO);
        $this->assertNotNull($editedUnitDTO);
        $this->assertEquals('Updated Unit Name', $editedUnitDTO->name);
        $this->assertInstanceOf(UnitDTO::class, $editedUnitDTO);
    }

    // test untuk edit unit yang tidak ada
    public function test_edit_non_existent_unit(): void
    {
        $nonExistentUnitId = '9999';
        $updatedUnitDTO = new UnitDTO;
        $updatedUnitDTO->name = 'Updated Name';

        $editedUnitDTO = $this->unitRepository->edit($nonExistentUnitId, $updatedUnitDTO);
        $this->assertNull($editedUnitDTO);
    }

    // test untuk edit unit dengan data kosong
    public function test_edit_unit_with_empty_data(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = $this->unitData['name'];

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);
        $this->assertNotNull($insertedUnitDTO);

        $insertedUnitId = $insertedUnitDTO->id;
        $updatedUnitDTO = new UnitDTO; // Data kosong

        $editedUnitDTO = $this->unitRepository->edit($insertedUnitId, $updatedUnitDTO);
        $this->assertNull($editedUnitDTO);
    }

    // test getById unit
    public function test_get_by_id_unit(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = $this->unitData['name'];

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);
        $this->assertNotNull($insertedUnitDTO);

        $insertedUnitId = $insertedUnitDTO->id;
        $fetchedUnitDTO = $this->unitRepository->getById($insertedUnitId);

        $this->assertNotNull($fetchedUnitDTO);
        $this->assertEquals($insertedUnitDTO->name, $fetchedUnitDTO->name);
        $this->assertEquals($insertedUnitDTO->id, $fetchedUnitDTO->id);
        $this->assertInstanceOf(UnitDTO::class, $fetchedUnitDTO);
    }

    // test getById unit yang tidak ada
    public function test_get_by_id_non_existent_unit(): void
    {
        $nonExistentUnitId = '9999';
        $fetchedUnitDTO = $this->unitRepository->getById($nonExistentUnitId);
        $this->assertNull($fetchedUnitDTO);
    }

    // test getLatestId unit
    public function test_get_latest_id_unit(): void
    {
        // Insert some units first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $unitDTO = new UnitDTO;
            $unitDTO->name = "Unit $i";

            $insertedUnitDTO = $this->unitRepository->insert($unitDTO);
            $latestInsertedId = $insertedUnitDTO->id;
        }

        $latestId = $this->unitRepository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId unit ketika tidak ada data
    public function test_get_latest_id_unit_when_no_data(): void
    {
        $latestId = $this->unitRepository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete unit
    public function test_delete_unit(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = $this->unitData['name'];

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);
        $this->assertNotNull($insertedUnitDTO);

        $insertedUnitId = $insertedUnitDTO->id;
        $deleteResult = $this->unitRepository->delete($insertedUnitId);
        $this->assertTrue($deleteResult);

        // Verify unit is deleted
        $fetchedUnitDTO = $this->unitRepository->getById($insertedUnitId);
        $this->assertNull($fetchedUnitDTO);
    }

    // test delete unit yang tidak ada
    public function test_delete_non_existent_unit(): void
    {
        $nonExistentUnitId = '9999';
        $deleteResult = $this->unitRepository->delete($nonExistentUnitId);
        $this->assertFalse($deleteResult);
    }

    // test insert multiple units dengan nama berbeda
    public function test_insert_multiple_units_with_different_names(): void
    {
        $unitsData = [
            'Tablet',
            'Capsule',
            'Sirup',
            'Injeksi',
            'Salep',
        ];

        foreach ($unitsData as $name) {
            $unitDTO = new UnitDTO;
            $unitDTO->name = $name;

            $insertedUnit = $this->unitRepository->insert($unitDTO);
            $this->assertNotNull($insertedUnit);
            $this->assertEquals($name, $insertedUnit->name);
        }

        $allUnits = $this->unitRepository->getAll();
        $this->assertCount(5, $allUnits);
    }

    // test untuk verify data completeness setelah insert
    public function test_insert_unit_data_completeness(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = 'Kapsul';

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);

        $this->assertNotNull($insertedUnitDTO);
        $this->assertNotNull($insertedUnitDTO->id);
        $this->assertEquals('Kapsul', $insertedUnitDTO->name);
        $this->assertNotNull($insertedUnitDTO->createdAt);
        $this->assertNotNull($insertedUnitDTO->updatedAt);
        $this->assertInstanceOf(\Carbon\CarbonInterface::class, $insertedUnitDTO->createdAt);
        $this->assertInstanceOf(\Carbon\CarbonInterface::class, $insertedUnitDTO->updatedAt);
    }

    // test edit unit dan verify timestamps berubah
    public function test_edit_unit_updates_timestamp(): void
    {
        $unitDTO = new UnitDTO;
        $unitDTO->name = 'Original Name';

        $insertedUnitDTO = $this->unitRepository->insert($unitDTO);
        $originalUpdatedAt = $insertedUnitDTO->updatedAt;

        // Sleep untuk memastikan timestamp berbeda
        sleep(1);

        $updateDTO = new UnitDTO;
        $updateDTO->name = 'New Name';

        $editedUnitDTO = $this->unitRepository->edit($insertedUnitDTO->id, $updateDTO);

        $this->assertNotNull($editedUnitDTO);
        $this->assertEquals('New Name', $editedUnitDTO->name);
        $this->assertNotEquals($originalUpdatedAt, $editedUnitDTO->updatedAt);
    }
}
