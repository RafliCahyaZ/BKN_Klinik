<?php

namespace Tests\Feature;

use App\DTO\MedicineDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\UnitDTO;
use App\DTO\UserDTO;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\UnitRepositoryEloquent;
use App\Repository\UserRepositoryEloquent;
use App\Type\TransactionType;
use App\Type\UserRole;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineStockTransactionRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    protected MedicineStockTransactionRepositoryEloquent $transactionRepository;

    protected MedicineRepositoryEloquent $medicineRepository;

    protected UserRepositoryEloquent $userRepository;

    protected $unitRepository;

    protected MedicineDTO $medicineDTO;

    protected UserDTO $inventoryOfficerDTO;

    protected UnitDTO $unitDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->medicineRepository = new MedicineRepositoryEloquent;
        $this->userRepository = new UserRepositoryEloquent;
        $this->unitRepository = new UnitRepositoryEloquent;
        $this->transactionRepository = new MedicineStockTransactionRepositoryEloquent($this->medicineRepository, $this->userRepository);

        $unitDTO = new UnitDTO;
        $unitDTO->name = 'Tablet';
        $this->unitDTO = $this->unitRepository->insert($unitDTO);

        $medicineDTO = new MedicineDTO;
        $medicineDTO->name = 'Paracetamol';
        $medicineDTO->stock = 100;
        $medicineDTO->unit = $this->unitDTO;
        $this->medicineDTO = $this->medicineRepository->insert($medicineDTO);

        $userDTO = new UserDTO;
        $userDTO->username = 'inventory_officer';
        $userDTO->fullname = 'Officer Name';
        $userDTO->email = 'officer@example.com';
        $userDTO->password = bcrypt('password');
        $userDTO->role = UserRole::INVENTORY_OFFICER;
        $this->inventoryOfficerDTO = $this->userRepository->insert($userDTO);
    }

    // test untuk insert medicine stock transaction (masuk)
    public function test_insert_medicine_stock_transaction_in_success(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
        $transactionDTO->expiredDate = Carbon::parse('2027-12-31');
        $transactionDTO->notes = 'Pembelian stok baru';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNotNull($insertedTransactionDTO);
        $this->assertEquals(TransactionType::IN, $insertedTransactionDTO->type);
        $this->assertEquals(50, $insertedTransactionDTO->quantity);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $insertedTransactionDTO);
        $this->assertInstanceOf(UserDTO::class, $insertedTransactionDTO->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $insertedTransactionDTO->medicine);
    }

    // test untuk insert medicine stock transaction (keluar)
    public function test_insert_medicine_stock_transaction_out_success(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::OUT;
        $transactionDTO->quantity = 30;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Penggunaan obat';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNotNull($insertedTransactionDTO);
        $this->assertEquals(TransactionType::OUT, $insertedTransactionDTO->type);
        $this->assertEquals(30, $insertedTransactionDTO->quantity);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $insertedTransactionDTO);
        $this->assertInstanceOf(UserDTO::class, $insertedTransactionDTO->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $insertedTransactionDTO->medicine);
    }

    // test untuk insert transaction gagal ketika medicine tidak ada
    public function test_insert_transaction_failed_when_medicine_not_exists(): void
    {
        // Create a fake medicine DTO with non-existent ID
        $fakeMedicineDTO = new MedicineDTO;
        $fakeMedicineDTO->id = '9999';
        $fakeMedicineDTO->name = 'Fake Medicine';
        $fakeMedicineDTO->stock = 0;
        $fakeMedicineDTO->unit = $this->unitDTO;

        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $fakeMedicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = null;

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNull($insertedTransactionDTO);
    }

    // test untuk get all transactions
    public function test_get_all_transactions(): void
    {
        // Insert some transactions first
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::IN;
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = "Transaction $i";

            $this->transactionRepository->insert($transactionDTO);
        }

        $transactions = $this->transactionRepository->getAll();
        $this->assertIsArray($transactions);
        $this->assertCount(3, $transactions);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $transactions[0]);
        $this->assertInstanceOf(UserDTO::class, $transactions[0]->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $transactions[0]->medicine);
    }

    // test untuk getall transactions ketika tidak ada data
    public function test_get_all_transactions_when_no_data(): void
    {
        $transactions = $this->transactionRepository->getAll();
        $this->assertIsArray($transactions);
        $this->assertCount(0, $transactions);
    }

    // test untuk edit transaction
    public function test_edit_transaction(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Original notes';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
        $this->assertNotNull($insertedTransactionDTO);

        $insertedTransactionId = $insertedTransactionDTO->id;
        $updatedTransactionDTO = new MedicineStockTransactionDTO;
        $updatedTransactionDTO->notes = 'Updated notes';
        $updatedTransactionDTO->quantity = 60;

        $editedTransactionDTO = $this->transactionRepository->edit($insertedTransactionId, $updatedTransactionDTO);
        $this->assertNotNull($editedTransactionDTO);
        $this->assertEquals('Updated notes', $editedTransactionDTO->notes);
        $this->assertEquals(60, $editedTransactionDTO->quantity);
        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $editedTransactionDTO);
    }

    // test untuk edit transaction yang tidak ada
    public function test_edit_non_existent_transaction(): void
    {
        $nonExistentTransactionId = '9999';
        $updatedTransactionDTO = new MedicineStockTransactionDTO;
        $updatedTransactionDTO->notes = 'Updated notes';

        $editedTransactionDTO = $this->transactionRepository->edit($nonExistentTransactionId, $updatedTransactionDTO);
        $this->assertNull($editedTransactionDTO);
    }

    // test untuk edit transaction dengan data kosong
    public function test_edit_transaction_with_empty_data(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Original notes';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
        $this->assertNotNull($insertedTransactionDTO);

        $insertedTransactionId = $insertedTransactionDTO->id;
        $updatedTransactionDTO = new MedicineStockTransactionDTO; // Data kosong

        $editedTransactionDTO = $this->transactionRepository->edit($insertedTransactionId, $updatedTransactionDTO);
        $this->assertNull($editedTransactionDTO);
    }

    // test getById transaction
    public function test_get_by_id_transaction(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
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
        $this->assertInstanceOf(UserDTO::class, $fetchedTransactionDTO->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $fetchedTransactionDTO->medicine);
    }

    // test getById transaction yang tidak ada
    public function test_get_by_id_non_existent_transaction(): void
    {
        $nonExistentTransactionId = '9999';
        $fetchedTransactionDTO = $this->transactionRepository->getById($nonExistentTransactionId);
        $this->assertNull($fetchedTransactionDTO);
    }

    // test getLatestId transaction
    public function test_get_latest_id_transaction(): void
    {
        // Insert some transactions first
        $latestInsertedId = null;
        for ($i = 1; $i <= 4; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::IN;
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);
            $latestInsertedId = $insertedTransactionDTO->id;
        }

        $latestId = $this->transactionRepository->getLatestId();
        $this->assertEquals($latestInsertedId, $latestId);
    }

    // test getLatestId transaction ketika tidak ada data
    public function test_get_latest_id_transaction_when_no_data(): void
    {
        $latestId = $this->transactionRepository->getLatestId();
        $this->assertEquals(0, $latestId);
    }

    // test delete transaction
    public function test_delete_transaction(): void
    {
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
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
    public function test_delete_non_existent_transaction(): void
    {
        $nonExistentTransactionId = '9999';
        $deleteResult = $this->transactionRepository->delete($nonExistentTransactionId);
        $this->assertFalse($deleteResult);
    }

    // test getByMedicineId
    public function test_get_by_medicine_id(): void
    {
        // Insert transactions for this medicine
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::IN;
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        $transactions = $this->transactionRepository->getByMedicineId($this->medicineDTO->id);
        $this->assertIsArray($transactions);
        $this->assertCount(3, $transactions);
        $this->assertEquals($this->medicineDTO->id, $transactions[0]->medicine->id);
        $this->assertInstanceOf(UserDTO::class, $transactions[0]->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $transactions[0]->medicine);
    }

    // test getByType untuk transaksi masuk
    public function test_get_by_type_in(): void
    {
        // Insert transactions with type 'in'
        for ($i = 1; $i <= 2; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::IN;
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        // Insert transactions with type 'out'
        for ($i = 1; $i <= 1; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::OUT;
            $transactionDTO->quantity = 10;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        $inTransactions = $this->transactionRepository->getByType(TransactionType::IN);
        $this->assertIsArray($inTransactions);
        $this->assertCount(2, $inTransactions);
        $this->assertEquals(TransactionType::IN, $inTransactions[0]->type);
        $this->assertInstanceOf(UserDTO::class, $inTransactions[0]->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $inTransactions[0]->medicine);
    }

    // test getByType untuk transaksi keluar
    public function test_get_by_type_out(): void
    {
        // Insert transactions with type 'out'
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::OUT;
            $transactionDTO->quantity = 10 * $i;
            $transactionDTO->expiredDate = null;
            $transactionDTO->notes = null;

            $this->transactionRepository->insert($transactionDTO);
        }

        $outTransactions = $this->transactionRepository->getByType(TransactionType::OUT);
        $this->assertIsArray($outTransactions);
        $this->assertCount(3, $outTransactions);
        $this->assertEquals(TransactionType::OUT, $outTransactions[0]->type);
        $this->assertInstanceOf(UserDTO::class, $outTransactions[0]->inventoryOfficer);
        $this->assertInstanceOf(MedicineDTO::class, $outTransactions[0]->medicine);
    }

    // test untuk verify medicine, officer dan unit tersedia
    public function test_dependencies_are_available(): void
    {
        $this->assertNotNull($this->medicineDTO);
        $this->assertNotNull($this->inventoryOfficerDTO);
        $this->assertNotNull($this->unitDTO);
        $this->assertEquals('Paracetamol', $this->medicineDTO->name);
        $this->assertEquals('inventory_officer', $this->inventoryOfficerDTO->username);
        $this->assertEquals('Tablet', $this->unitDTO->name);
    }

    // test untuk insertMany transactions sukses
    public function test_insert_many_transactions_success(): void
    {
        $transactions = [];

        // Buat 3 transaksi untuk di-insert sekaligus
        for ($i = 1; $i <= 3; $i++) {
            $transactionDTO = new MedicineStockTransactionDTO;
            $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
            $transactionDTO->medicine = $this->medicineDTO;
            $transactionDTO->registration = null;
            $transactionDTO->type = TransactionType::IN;
            $transactionDTO->quantity = 20 * $i;
            $transactionDTO->expiredDate = Carbon::parse('2027-12-31');
            $transactionDTO->notes = "Batch transaction $i";

            $transactions[] = $transactionDTO;
        }

        $result = $this->transactionRepository->insertMany($transactions);

        // insertMany return boolean
        $this->assertTrue($result);

        // Verify total data di database
        $allTransactions = $this->transactionRepository->getAll();
        $this->assertCount(3, $allTransactions);
    }

    // test untuk insertMany transactions gagal ketika salah satu data tidak valid
    public function test_insert_many_transactions_failed_when_invalid_data(): void
    {
        $transactions = [];

        // Transaksi pertama: valid
        $transaction1 = new MedicineStockTransactionDTO;
        $transaction1->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction1->medicine = $this->medicineDTO;
        $transaction1->registration = null;
        $transaction1->type = TransactionType::IN;
        $transaction1->quantity = 20;
        $transaction1->expiredDate = null;
        $transaction1->notes = 'Valid transaction';
        $transactions[] = $transaction1;

        // Transaksi kedua: tidak valid (medicine tidak ada)
        $fakeMedicineDTO = new MedicineDTO;
        $fakeMedicineDTO->id = '9999';
        $fakeMedicineDTO->name = 'Fake Medicine';
        $fakeMedicineDTO->stock = 0;
        $fakeMedicineDTO->unit = $this->unitDTO;

        $transaction2 = new MedicineStockTransactionDTO;
        $transaction2->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction2->medicine = $fakeMedicineDTO;
        $transaction2->registration = null;
        $transaction2->type = TransactionType::IN;
        $transaction2->quantity = 30;
        $transaction2->expiredDate = null;
        $transaction2->notes = 'Invalid transaction';
        $transactions[] = $transaction2;

        // Transaksi ketiga: valid
        $transaction3 = new MedicineStockTransactionDTO;
        $transaction3->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction3->medicine = $this->medicineDTO;
        $transaction3->registration = null;
        $transaction3->type = TransactionType::OUT;
        $transaction3->quantity = 10;
        $transaction3->expiredDate = null;
        $transaction3->notes = 'Valid transaction 2';
        $transactions[] = $transaction3;

        $result = $this->transactionRepository->insertMany($transactions);

        // insertMany return false karena ada data tidak valid
        $this->assertFalse($result);

        // Verify tidak ada transaksi yang ter-insert (rollback semua)
        $allTransactions = $this->transactionRepository->getAll();
        $this->assertCount(0, $allTransactions);
    }

    // test untuk insert dengan auto calculate beginning stock dan last stock (type in)
    public function test_insert_transaction_with_auto_calculate_stock_in(): void
    {
        // Stok awal medicine adalah 100 (dari setUp)
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::IN;
        $transactionDTO->quantity = 50;
        $transactionDTO->expiredDate = Carbon::parse('2027-12-31');
        $transactionDTO->notes = 'Pembelian stok baru';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNotNull($insertedTransactionDTO);
        $this->assertEquals(TransactionType::IN, $insertedTransactionDTO->type);
        $this->assertEquals(50, $insertedTransactionDTO->quantity);

        // Verify beginning stock adalah stok awal medicine (100)
        $this->assertEquals(100, $insertedTransactionDTO->beginningStock);

        // Verify last stock = beginning stock + quantity (100 + 50 = 150)
        $this->assertEquals(150, $insertedTransactionDTO->lastStock);

        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $insertedTransactionDTO);
    }

    // test untuk insert dengan auto calculate beginning stock dan last stock (type out)
    public function test_insert_transaction_with_auto_calculate_stock_out(): void
    {
        // Stok awal medicine adalah 100 (dari setUp)
        $transactionDTO = new MedicineStockTransactionDTO;
        $transactionDTO->inventoryOfficer = $this->inventoryOfficerDTO;
        $transactionDTO->medicine = $this->medicineDTO;
        $transactionDTO->registration = null;
        $transactionDTO->type = TransactionType::OUT;
        $transactionDTO->quantity = 30;
        $transactionDTO->expiredDate = null;
        $transactionDTO->notes = 'Penggunaan obat';

        $insertedTransactionDTO = $this->transactionRepository->insert($transactionDTO);

        $this->assertNotNull($insertedTransactionDTO);
        $this->assertEquals(TransactionType::OUT, $insertedTransactionDTO->type);
        $this->assertEquals(30, $insertedTransactionDTO->quantity);

        // Verify beginning stock adalah stok awal medicine (100)
        $this->assertEquals(100, $insertedTransactionDTO->beginningStock);

        // Verify last stock = beginning stock - quantity (100 - 30 = 70)
        $this->assertEquals(70, $insertedTransactionDTO->lastStock);

        $this->assertInstanceOf(MedicineStockTransactionDTO::class, $insertedTransactionDTO);
    }

    // test untuk insertMany dengan auto calculate beginning stock dan last stock
    public function test_insert_many_transactions_with_auto_calculate_stock(): void
    {
        $transactions = [];

        // Transaksi 1: type in
        $transaction1 = new MedicineStockTransactionDTO;
        $transaction1->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction1->medicine = $this->medicineDTO;
        $transaction1->registration = null;
        $transaction1->type = TransactionType::IN;
        $transaction1->quantity = 25;
        $transaction1->expiredDate = Carbon::parse('2027-12-31');
        $transaction1->notes = 'Batch transaction 1';
        $transactions[] = $transaction1;

        // Transaksi 2: type out
        $transaction2 = new MedicineStockTransactionDTO;
        $transaction2->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction2->medicine = $this->medicineDTO;
        $transaction2->registration = null;
        $transaction2->type = TransactionType::OUT;
        $transaction2->quantity = 15;
        $transaction2->expiredDate = null;
        $transaction2->notes = 'Batch transaction 2';
        $transactions[] = $transaction2;

        // Transaksi 3: type in
        $transaction3 = new MedicineStockTransactionDTO;
        $transaction3->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction3->medicine = $this->medicineDTO;
        $transaction3->registration = null;
        $transaction3->type = TransactionType::IN;
        $transaction3->quantity = 40;
        $transaction3->expiredDate = Carbon::parse('2028-06-30');
        $transaction3->notes = 'Batch transaction 3';
        $transactions[] = $transaction3;

        $result = $this->transactionRepository->insertMany($transactions);

        // Verify insert many berhasil
        $this->assertTrue($result);

        // Verify total data di database
        $allTransactions = $this->transactionRepository->getAll();
        $this->assertCount(3, $allTransactions);

        // Verify perhitungan stok untuk setiap transaksi
        // Semua transaksi menggunakan beginning stock yang sama (100) karena insertMany

        // Transaction 1: in, beginning = 100, quantity = 25, last = 125
        $this->assertEquals(100, $allTransactions[0]->beginningStock);
        $this->assertEquals(125, $allTransactions[0]->lastStock);

        // Transaction 2: out, beginning = 100, quantity = 15, last = 85
        $this->assertEquals(100, $allTransactions[1]->beginningStock);
        $this->assertEquals(85, $allTransactions[1]->lastStock);

        // Transaction 3: in, beginning = 100, quantity = 40, last = 140
        $this->assertEquals(100, $allTransactions[2]->beginningStock);
        $this->assertEquals(140, $allTransactions[2]->lastStock);
    }

    // test untuk verify perhitungan stok dengan multiple medicine berbeda
    public function test_insert_transaction_with_different_medicines(): void
    {
        // Buat medicine kedua dengan stok berbeda
        $medicineDTO2 = new MedicineDTO;
        $medicineDTO2->name = 'Amoxicillin';
        $medicineDTO2->stock = 50; // Stok berbeda
        $medicineDTO2->unit = $this->unitDTO;
        $insertedMedicine2 = $this->medicineRepository->insert($medicineDTO2);

        // Insert transaksi untuk medicine pertama (stok 100)
        $transaction1 = new MedicineStockTransactionDTO;
        $transaction1->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction1->medicine = $this->medicineDTO;
        $transaction1->registration = null;
        $transaction1->type = TransactionType::IN;
        $transaction1->quantity = 20;
        $transaction1->expiredDate = null;
        $transaction1->notes = 'Transaction for Paracetamol';

        $insertedTransaction1 = $this->transactionRepository->insert($transaction1);

        // Verify stok medicine pertama
        $this->assertEquals(100, $insertedTransaction1->beginningStock);
        $this->assertEquals(120, $insertedTransaction1->lastStock);

        // Insert transaksi untuk medicine kedua (stok 50)
        $transaction2 = new MedicineStockTransactionDTO;
        $transaction2->inventoryOfficer = $this->inventoryOfficerDTO;
        $transaction2->medicine = $insertedMedicine2;
        $transaction2->registration = null;
        $transaction2->type = TransactionType::OUT;
        $transaction2->quantity = 10;
        $transaction2->expiredDate = null;
        $transaction2->notes = 'Transaction for Amoxicillin';

        $insertedTransaction2 = $this->transactionRepository->insert($transaction2);

        // Verify stok medicine kedua
        $this->assertEquals(50, $insertedTransaction2->beginningStock);
        $this->assertEquals(40, $insertedTransaction2->lastStock);
    }
}
