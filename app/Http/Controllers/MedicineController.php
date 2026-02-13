<?php

namespace App\Http\Controllers;

use App\DTO\MedicineDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\UnitDTO;
use App\DTO\UserDTO;
use App\Helper\TransactionTypeHelper;
use App\Repository\MedicineRepository;
use App\Repository\MedicineStockTransactionRepository;
use App\Repository\UnitRepository;
use App\Type\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicineController extends Controller
{
    public function __construct(
        private MedicineRepository $medicineRepository,
        private UnitRepository $unitRepository,
        private MedicineStockTransactionRepository $medicineStockTransactionRepository
    ) {}

    public function history($id)
    {
        $medicine = $this->medicineRepository->getById($id);

        if (!$medicine) {
            abort(404);
        }

        $medicineStockTransactions = $this->medicineStockTransactionRepository->getByMedicineId($id);

        return view("dashboard.medicine.history", [
            "medicineStockTransactions" => $medicineStockTransactions,
            "medicine" => $medicine
        ]);
    }

    public function getTransaction($id)
    {
        $medicine = $this->medicineRepository->getById($id);

        if (!$medicine) {
            abort(404);
        }

        $transactionTypes = TransactionTypeHelper::getArrayOptions();
        return view("dashboard.medicine.transaction", [
            'transactionTypes' => $transactionTypes,
            "medicine" => $medicine
        ]);
    }

    public function getAllTransaction()
    {
        $transactions = $this->medicineStockTransactionRepository->getAll();
        $transactionTypes = TransactionTypeHelper::getArrayOptions();
        return view("dashboard.medicine-transaction.index", [
            'transactions' => $transactions,
            'transactionTypes' => $transactionTypes
        ]);
    }

    public function createTransaction()
    {
        $transactionTypes = TransactionTypeHelper::getArrayOptions();
        $medicines = $this->medicineRepository->getAll();
        return view("dashboard.medicine-transaction.create", [
            'transactionTypes' => $transactionTypes,
            'medicines' => $medicines
        ]);
    }

    public function storeTransaction(Request $request)
    {

        try {
            $validated = $request->validate([
                "medicineId" => [
                    'required',
                ],
                "transactionType" => [
                    'required',
                    Rule::in(TransactionTypeHelper::getArrayOptions()),
                ],
                'quantity' => [
                    'required',
                    'integer',
                    'min:1'
                ]
            ]);

            $medicineStockTransaction = new MedicineStockTransactionDTO();

            $medicineStockTransaction->inventoryOfficer = new UserDTO();
            $medicineStockTransaction->inventoryOfficer->id = Auth::id();

            $medicineStockTransaction->type = TransactionTypeHelper::stringToTransactionRole($validated["transactionType"]);
            $medicineStockTransaction->quantity = $validated["quantity"];
            $medicineStockTransaction->createdAt = Carbon::now();
            $medicineStockTransaction->updatedAt = Carbon::now();

            if ($request["notes"]) {
                $medicineStockTransaction->notes = $request["notes"];
            }

            $medicine = $this->medicineRepository->getById($validated["medicineId"]);

            if (!$medicine) {
                abort(404);
            }

            $medicineStockTransaction->medicine = new MedicineDTO();
            $medicineStockTransaction->medicine->id = $validated["medicineId"];
            $medicineStockTransaction->beginningStock = $medicine->stock;

            if ($medicine->stock + ($medicineStockTransaction->type === TransactionType::IN ? $medicineStockTransaction->quantity : -$medicineStockTransaction->quantity) < 0) {
                return redirect()->route('medicine.index')->with("failed", "Stock medicine cannot be negative");
            }

            if ($medicineStockTransaction->type !== TransactionType::ADJUSTMENT) {
                $medicine->stock = $medicine->stock + ($medicineStockTransaction->type === TransactionType::IN ? $medicineStockTransaction->quantity : -$medicineStockTransaction->quantity);
            } else {
                $medicine->stock = $medicineStockTransaction->quantity;
            }

            $updatedMedicine = $this->medicineRepository->edit($medicine->id, $medicine);
            $medicineStockTransaction->lastStock = $medicine->stock;

            if (!$updatedMedicine) {
                return redirect()->route('medicine.index')->with("failed", "Update medicine stock failed");
            }

            if (!$this->medicineStockTransactionRepository->insert($medicineStockTransaction)) {
                return redirect()->route('medicine.index')->with("failed", "Insert medicine failed");
            }

            return redirect()->route("medicine.transaction.index")->with("success", "Success Create Transaction");
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function createTransactionReport()
    {
        return view("dashboard.medicine-transaction.report.create");
    }

    public function printTransactionReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'start-date' => [
                    'required',
                    'date',
                ],
                'end-date' => [
                    'required',
                    'date',
                    'after_or_equal:start-date',
                ],
            ]);

            $startDate = Carbon::parse($validated['start-date'])->startOfDay();
            $endDate = Carbon::parse($validated['end-date'])->endOfDay();

            $medicineStockTransactions = $this->medicineStockTransactionRepository->getByPeriod($startDate, $endDate);
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $pdf = Pdf::loadView('pdf.medicine-transaction-report', compact('medicineStockTransactions', 'startDate', 'endDate'));
            return $pdf->stream("laporan_periode_transaksi_obat_{$timestamp}.pdf");
        } catch (\Throwable $th) {
            return back()->with("failed", "Terjadi kesalahan saat membuat laporan");
        }
    }

    public function transactionAction($id, Request $request)
    {
        $validated = $request->validate([
            "transactionType" => [
                'required',
                Rule::in(TransactionTypeHelper::getArrayOptions()),
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ]);

        $medicineStockTransaction = new MedicineStockTransactionDTO();

        $medicineStockTransaction->inventoryOfficer = new UserDTO();
        $medicineStockTransaction->inventoryOfficer->id = Auth::id();

        $medicineStockTransaction->medicine = new MedicineDTO();
        $medicineStockTransaction->medicine->id = $id;

        $medicineStockTransaction->type = TransactionTypeHelper::stringToTransactionRole($validated["transactionType"]);
        $medicineStockTransaction->quantity = $validated["quantity"];

        $medicineStockTransaction->createdAt = Carbon::now();
        $medicineStockTransaction->updatedAt = Carbon::now();

        if ($request["notes"]) {
            $medicineStockTransaction->notes = $request["notes"];
        }

        if (!$this->medicineStockTransactionRepository->insert($medicineStockTransaction)) {
            return redirect()->route('medicine.index')->with("failed", "Insert medicine failed");
        }

        return redirect()->route("medicine.history", $id)->with("success", "Success Create Transaction");
    }

    public function printAllTransaction()
    {
        $medicineStockTransactions = $this->medicineStockTransactionRepository->getAll();
        $timestamp = Carbon::now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('pdf.all-medicine-transactions', compact('medicineStockTransactions'));
        return $pdf->stream("laporan_transaksi_obat_{$timestamp}.pdf");
    }

    public function index()
    {
        $medicines = $this->medicineRepository->getAll();
        return view("dashboard.medicine.home", [
            "medicines" => $medicines
        ]);
    }

    public function edit($id)
    {
        $medicine = $this->medicineRepository->getById($id);

        if (!$medicine) {
            abort(404);
        }

        $units = $this->unitRepository->getAll();

        return view("dashboard.medicine.edit", [
            "medicine" => $medicine,
            "units" => $units
        ]);
    }

    public function editAction($id, Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],
            'unit' => [
                'required'
            ]
        ]);
        $medicine = $this->medicineRepository->getById($id);

        if (!$medicine) {
            abort(404);
        }

        $medicine->name = $validated["name"];
        $medicine->unit->id = $validated["unit"];

        if (!$this->medicineRepository->edit($id, $medicine)) {
            return redirect()->route("medicine.index")->with("failed", "Failed! Data gagal diedit");
        }

        return redirect()->route("medicine.index")->with("success", "Sukses! Data berhasil diedit");
    }

    public function create()
    {
        $units = $this->unitRepository->getAll();
        return view("dashboard.medicine.create", [
            "units" => $units
        ]);
    }

    public function destroy($id)
    {
        $medicine = $this->medicineRepository->getById($id);
        if (!$medicine) {
            abort(404);
        }

        if (!$this->medicineRepository->delete($id)) {
            return redirect()->route("medicine.index")->with("failed", "Failed! Data gagal dihapus");
        }

        return redirect()->route("medicine.index")->with("success", "Sukses! Data berhasil dihapus");
    }

    public function print()
    {
        $medicines = $this->medicineRepository->getAll();
        $timestamp = Carbon::now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('pdf.medicines', compact('medicines'));
        return $pdf->stream("master_data_obat_{$timestamp}.pdf");
    }

    public function printHistory($id)
    {
        $medicine = $this->medicineRepository->getById($id);

        if (!$medicine) {
            abort(404);
        }

        $medicineStockTransactions = $this->medicineStockTransactionRepository->getByMedicineId($id);
        $timestamp = Carbon::now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('pdf.medicine-transaction', compact('medicine', 'medicineStockTransactions'));
        return $pdf->stream("histori_transaksi_{$medicine->name}_{$timestamp}.pdf");
    }

    public function store(Request $request)
    {
        $validated = $this->getMedicineInputValidation($request);

        $medicineDto = new MedicineDTO();
        $medicineDto->name = $validated["name"];
        $medicineDto->stock = $validated["stock"];
        $medicineDto->unit = new UnitDTO();
        $medicineDto->unit->id = $validated["unit"];

        if (!$this->medicineRepository->insert($medicineDto)) {
            return redirect()->route("medicine.index")->with("failed", "Failed! Data gagal disimpan");
        }

        return redirect()->route("medicine.index")->with("success", "Sukses! Data berhasil disimpan");
    }

    public function getMedicineInputValidation(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],
            'stock' => [
                'required',
                'integer',
                'min:0'
            ],
            'unit' => [
                'required'
            ]
        ]);

        return $validated;
    }
}
