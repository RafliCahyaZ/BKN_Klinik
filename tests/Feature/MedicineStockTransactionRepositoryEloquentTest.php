<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\UserRepositoryEloquent;
use App\Repository\UnitRepositoryEloquent;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\MedicineDTO;
use App\DTO\UserDTO;
use App\DTO\UnitDTO;
use App\Type\UserRole;
use Carbon\Carbon;

class MedicineStockTransactionRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected MedicineStockTransactionRepositoryEloquent $transactionRepository;
    protected MedicineRepositoryEloquent $medicineRepository;
    protected UserRepositoryEloquent $userRepository;
    protected  $unitRepository;

    protected MedicineDTO $medicineDTO;
    protected UserDTO $inventoryOfficerDTO;
    protected UnitDTO $unitDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = new MedicineStockTransactionRepositoryEloquent();
        $this->medicineRepository = new MedicineRepositoryEloquent();
        $this->userRepository = new UserRepositoryEloquent();
        $this->unitRepository = new UnitRepositoryEloquent();

        $unitDTO = new UnitDTO();
        $unitDTO->name = 'Tablet';
        $this->unitDTO = $this->unitRepository->insert($unitDTO);

        $medicineDTO = new MedicineDTO();
        $medicineDTO->name = 'Paracetamol';
        $medicineDTO->stock = 100;
        $medicineDTO->unit = $this->unitDTO;
        $this->medicineDTO = $this->medicineRepository->insert($medicineDTO);

        $userDTO = new UserDTO();
        $userDTO->username = 'inventory_officer';
        $userDTO->fullname = 'Officer Name';
        $userDTO->email = 'officer@example.com';
        $userDTO->password = bcrypt('password');
        $userDTO->role = UserRole::INVENTORY_OFFICER;
        $this->inventoryOfficerDTO = $this->userRepository->insert($userDTO);
    }

    // test untuk insert medicine stock transaction (masuk)
    public function testInsertMedicineStockTransactionInSuccess(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = $this->medicineDTO->id;
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'in';
        $transactionDTO->quantity = 50;
        $transactionDTO->beginningStock = 100;
        $transactionDTO->lastStock = 150;
        $transactionDTO->expiredDate = Carbon::parse('2027-12-31');
        $transactionDTO->notes = 'Pembelian stok baru';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNotNull($insertedTransactionDTO);
        $this->assertEquals('in', $insertedTransactionDTO->type);
        $this->assertEquals(50, $insertedTransactionDTO->quantity);
        $this->assertEquals(100, $insertedTransactionDTO->beginningStock);
        $this->assertEquals(150, $insertedTransactionDTO->lastStock);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $insertedTransactionDTO);
    }

    // test untuk insert medicine stock transaction (keluar)
    public function testInsertMedicineStockTransactionOutSuccess(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = $this->medicineDTO->id;
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'out';
        $transactionDTO->quantity = 30;
        $transactionDTO->beginningStock = 100;
        $transactionDTO->lastStock = 70;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Penggunaan obat';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNotNull($insertedTransactionDTO);
        $this->assertEquals('out', $insertedTransactionDTO->type);
        $this->assertEquals(30, $insertedTransactionDTO->quantity);
        $this->assertEquals(70, $insertedTransactionDTO->lastStock);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $insertedTransactionDTO);
    }

    // test untuk insert transaction gagal ketika medicine tidak ada
    public function testInsertTransactionFailedWhenMedicineNotExists(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = '9999'; // Non-existent medicine
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'in';
        $transactionDTO->quantity = 50;
        $transactionDTO->beginningStock = 0;
        $transactionDTO->lastStock = 50;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = null;

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNull($insertedTransactionDTO);
    }

    // test untuk get all transactions
    public function testGetAllTransactions(): void
    {
        // Insert some transactions first
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO();
            $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
            $transactionDTO->medicineId = $this->medicineDTO->id;
            $transactionDTO->registrationId = null;
            $transactionDTO->type = 'in';
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->beginningStock = 100;
            $transactionDTO->lastStock = 100 + (10 * $i);
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = "Transaction $i";

            $this->transactionRepository->insert($transactionDTO);
        }

        $transactions = $this->transactionRepository->getAll();
        $this->assertIsArray($transactions);
        $this->assertCount(3, $transactions);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $transactions[0]);
    }

    // test untuk getall transactions ketika tidak ada data
    public function testGetAllTransactionsWhenNoData(): void
    {
        $transactions = $this->transactionRepository->getAll();
        $this->assertIsArray($transactions);
        $this->assertCount(0, $transactions);
    }

    // test untuk edit transaction
    public function testEditTransaction(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = $this->medicineDTO->id;
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'in';
        $transactionDTO->quantity = 50;
        $transactionDTO->beginningStock = 100;
        $transactionDTO->lastStock = 150;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Original notes';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
        $this->assertNotNull($insertedTransactionDTO);

        $insertedTransactionId = $insertedTransactionDTO->id;
        $updatedTransactionDTO = new MedicineStockTransactionDTO();
        $updatedTransactionDTO->notes = "Updated notes";
        $updatedTransactionDTO->quantity = 60;

        $editedTransactionDTO = $this->transactionRepository->edit($insertedTransactionId, $updatedTransactionDTO);
        $this->assertNotNull($editedTransactionDTO);
        $this->assertEquals("Updated notes", $editedTransactionDTO->notes);
        $this->assertEquals(60, $editedTransactionDTO->quantity);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $editedTransactionDTO);
    }

    // test untuk edit transaction yang tidak ada
    public function testEditNonExistentTransaction(): void
    {
        $nonExistentTransactionId = '9999';
        $updatedTransactionDTO = new MedicineStockTransactionDTO();
        $updatedTransactionDTO->notes = "Updated notes";

        $editedTransactionDTO = $this->transactionRepository->edit($nonExistentTransactionId, $updatedTransactionDTO);
        $this->assertNull($editedTransactionDTO);
    }

    // test untuk edit transaction dengan data kosong
    public function testEditTransactionWithEmptyData(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = $this->medicineDTO->id;
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'in';
        $transactionDTO->quantity = 50;
        $transactionDTO->beginningStock = 100;
        $transactionDTO->lastStock = 150;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Original notes';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
        $this->assertNotNull($insertedTransactionDTO);

        $insertedTransactionId = $insertedTransactionDTO->id;
        $updatedTransactionDTO = new MedicineStockTransactionDTO(); // Data kosong

        $editedTransactionDTO = $this->transactionRepository->edit($insertedTransactionId, $updatedTransactionDTO);
        $this->assertNull($editedTransactionDTO);
    }

    // test getById transaction
    public function testGetByIdTransaction(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = $this->medicineDTO->id;
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'in';
        $transactionDTO->quantity = 50;
        $transactionDTO->beginningStock = 100;
        $transactionDTO->lastStock = 150;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Test transaction';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
        $this->assertNotNull($insertedTransactionDTO);

        $insertedTransactionId = $insertedTransactionDTO->id;
        $fetchedTransactionDTO = $this->transactionRepository->getById($insertedTransactionId);

        $this->assertNotNull($fetchedTransactionDTO);
        $this->assertEquals($insertedTransactionDTO->quantity, $fetchedTransactionDTO->quantity);
        $this->assertEquals($insertedTransactionDTO->type, $fetchedTransactionDTO->type);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $fetchedTransactionDTO);
    }

    // test getById transaction yang tidak ada
    public function testGetByIdNonExistentTransaction(): void
    {
        $nonExistentTransactionId = '9999';
        $fetchedTransactionDTO = $this->transactionRepository->getById($nonExistentTransactionId);
        $this->assertNull($fetchedTransactionDTO);
    }

    // test getLatestId transaction
    public function testGetLatestIdTransaction(): void
    {
        // Insert some transactions first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO();
            $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
            $transactionDTO->medicineId = $this->medicineDTO->id;
            $transactionDTO->registrationId = null;
            $transactionDTO->type = 'in';
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->beginningStock = 100;
            $transactionDTO->lastStock = 100 + (10 * $i);
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
            $latestInsertedId = $insertedTransactionDTO->id;
        }

        $latestId = $this->transactionRepository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId transaction ketika tidak ada data
    public function testGetLatestIdTransactionWhenNoData(): void
    {
        $latestId = $this->transactionRepository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete transaction
    public function testDeleteTransaction(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO();
        $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
        $transactionDTO->medicineId = $this->medicineDTO->id;
        $transactionDTO->registrationId = null;
        $transactionDTO->type = 'in';
        $transactionDTO->quantity = 50;
        $transactionDTO->beginningStock = 100;
        $transactionDTO->lastStock = 150;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = null;

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
        $this->assertNotNull($insertedTransactionDTO);

        $insertedTransactionId = $insertedTransactionDTO->id;
        $deleteResult = $this->transactionRepository->delete($insertedTransactionId);
        $this->assertTrue($deleteResult);

        // Verify transaction is deleted
        $fetchedTransactionDTO = $this->transactionRepository->getById($insertedTransactionId);
        $this->assertNull($fetchedTransactionDTO);
    }

    // test delete transaction yang tidak ada
    public function testDeleteNonExistentTransaction(): void
    {
        $nonExistentTransactionId = '9999';
        $deleteResult = $this->transactionRepository->delete($nonExistentTransactionId);
        $this->assertFalse($deleteResult);
    }

    // test getByMedicineId
    public function testGetByMedicineId(): void
    {
        // Insert transactions for this medicine
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO();
            $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
            $transactionDTO->medicineId = $this->medicineDTO->id;
            $transactionDTO->registrationId = null;
            $transactionDTO->type = 'in';
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->beginningStock = 100;
            $transactionDTO->lastStock = 100 + (10 * $i);
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        $transactions = $this->transactionRepository->getByMedicineId($this->medicineDTO->id);
        $this->assertIsArray($transactions);
        $this->assertCount(3, $transactions);
        $this->assertEquals($this->medicineDTO->id, $transactions[0]->medicineId);
    }

    // test getByType untuk transaksi masuk
    public function testGetByTypeIn(): void
    {
        // Insert transactions with type 'in'
        for ($i = 1; $i <= 2; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO();
            $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
            $transactionDTO->medicineId = $this->medicineDTO->id;
            $transactionDTO->registrationId = null;
            $transactionDTO->type = 'in';
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->beginningStock = 100;
            $transactionDTO->lastStock = 100 + (10 * $i);
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        // Insert transactions with type 'out'
        for ($i = 1; $i <= 1; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO();
            $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
            $transactionDTO->medicineId = $this->medicineDTO->id;
            $transactionDTO->registrationId = null;
            $transactionDTO->type = 'out';
            $transactionDTO->quantity = 10;
            $transactionDTO->beginningStock = 100;
            $transactionDTO->lastStock = 90;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        $inTransactions = $this->transactionRepository->getByType('in');
        $this->assertIsArray($inTransactions);
        $this->assertCount(2, $inTransactions);
        $this->assertEquals('in', $inTransactions[0]->type);
    }

    // test getByType untuk transaksi keluar
    public function testGetByTypeOut(): void
    {
        // Insert transactions with type 'out'
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO();
            $transactionDTO->inventoryOfficerId = $this->inventoryOfficerDTO->id;
            $transactionDTO->medicineId = $this->medicineDTO->id;
            $transactionDTO->registrationId = null;
            $transactionDTO->type = 'out';
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->beginningStock = 100;
            $transactionDTO->lastStock = 100 - (10 * $i);
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        $outTransactions = $this->transactionRepository->getByType('out');
        $this->assertIsArray($outTransactions);
        $this->assertCount(3, $outTransactions);
        $this->assertEquals('out', $outTransactions[0]->type);
    }

    // test untuk verify medicine, officer dan unit tersedia
    public function testDependenciesAreAvailable(): void
    {
        $this->assertNotNull($this->medicineDTO);
        $this->assertNotNull($this->inventoryOfficerDTO);
        $this->assertNotNull($this->unitDTO);
        $this->assertEquals('Paracetamol', $this->medicineDTO->name);
        $this->assertEquals('inventory_officer', $this->inventoryOfficerDTO->username);
        $this->assertEquals('Tablet', $this->unitDTO->name);
    }
}
