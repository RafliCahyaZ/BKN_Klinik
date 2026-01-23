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

    public function transaction($id)
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

        $medicineStockTransaction->expiredDate = null;
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

    public function index()
    {
        $medicines = $this->medicineRepository->getAll();
        return view("dashboard.medicine.home", [
            "medicines" => $medicines
        ]);
        /* return view("dashboard.treatment.home", [ */
        /*     "treatments" => $treatmentRepository->getAll() */
        /* ]); */
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
