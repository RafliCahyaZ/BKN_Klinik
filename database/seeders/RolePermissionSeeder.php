<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(["name" => "get-all-patient"]);
        Permission::create(["name" => "get-detail-patient"]);
        Permission::create(["name" => "create-patient"]);
        Permission::create(["name" => "edit-patient"]);
        Permission::create(["name" => "delete-patient"]);
        Permission::create(["name" => "print-patient"]);
        Permission::create(["name" => "register-patient"]);
        Permission::create(["name" => "get-report-patient"]);

        Permission::create(["name" => "get-all-diagnosis"]);
        Permission::create(["name" => "get-detail-diagnosis"]);
        Permission::create(["name" => "create-diagnosis"]);
        Permission::create(["name" => "edit-diagnosis"]);
        Permission::create(["name" => "delete-diagnosis"]);
        Permission::create(["name" => "print-diagnosis"]);

        Permission::create(["name" => "get-all-treatment"]);
        Permission::create(["name" => "get-detail-treatment"]);
        Permission::create(["name" => "create-treatment"]);
        Permission::create(["name" => "edit-treatment"]);
        Permission::create(["name" => "delete-treatment"]);
        Permission::create(["name" => "print-treatment"]);

        Permission::create(["name" => "get-all-medicine"]);
        Permission::create(["name" => "get-detail-medicine"]);
        Permission::create(["name" => "create-medicine"]);
        Permission::create(["name" => "edit-medicine"]);
        Permission::create(["name" => "delete-medicine"]);
        Permission::create(["name" => "print-medicine"]);

        Permission::create(["name" => "get-all-transaction-medicine"]);
        Permission::create(["name" => "create-transaction-medicine"]);
        Permission::create(["name" => "print-transaction-medicine"]);
        Permission::create(["name" => "get-report-transaction-medicine"]);

        Permission::create(["name" => "get-all-appointment"]);
        Permission::create(["name" => "create-appointment"]);
        Permission::create(["name" => "get-history-appointment"]);
        Permission::create(["name" => "process-appointment"]);
        Permission::create(["name" => "cancel-appointment"]);

        Permission::create(["name" => "get-all-user"]);
        Permission::create(["name" => "get-detail-user"]);
        Permission::create(["name" => "create-user"]);
        Permission::create(["name" => "edit-user"]);
        Permission::create(["name" => "delete-user"]);
        Permission::create(["name" => "print-user"]);

        $this->adminPermission();
        $this->officerPermission();
        $this->inventoryOfficerPermission();
        $this->doctorPermission();
    }

    private function adminPermission(): void
    {
        Role::create(['name' => 'admin']);

        $adminRole = Role::findByName("admin");

        $adminRole->givePermissionTo("get-all-patient");
        $adminRole->givePermissionTo("get-detail-patient");
        $adminRole->givePermissionTo("create-patient");
        $adminRole->givePermissionTo("edit-patient");
        $adminRole->givePermissionTo("delete-patient");
        $adminRole->givePermissionTo("print-patient");
        $adminRole->givePermissionTo("register-patient");

        $adminRole->givePermissionTo("get-all-user");
        $adminRole->givePermissionTo("get-detail-user");
        $adminRole->givePermissionTo("create-user");
        $adminRole->givePermissionTo("edit-user");
        $adminRole->givePermissionTo("delete-user");
        $adminRole->givePermissionTo("print-user");

        $adminRole->givePermissionTo("get-all-treatment");
        $adminRole->givePermissionTo("get-detail-treatment");
        $adminRole->givePermissionTo("create-treatment");
        $adminRole->givePermissionTo("edit-treatment");
        $adminRole->givePermissionTo("delete-treatment");
        $adminRole->givePermissionTo("print-treatment");

        $adminRole->givePermissionTo("get-all-medicine");
        $adminRole->givePermissionTo("get-detail-medicine");
        $adminRole->givePermissionTo("create-medicine");
        $adminRole->givePermissionTo("edit-medicine");
        $adminRole->givePermissionTo("delete-medicine");
        $adminRole->givePermissionTo("print-medicine");

        $adminRole->givePermissionTo("get-all-diagnosis");
        $adminRole->givePermissionTo("get-detail-diagnosis");
        $adminRole->givePermissionTo("create-diagnosis");
        $adminRole->givePermissionTo("edit-diagnosis");
        $adminRole->givePermissionTo("delete-diagnosis");
        $adminRole->givePermissionTo("print-diagnosis");

        $users = User::where('role', 'admin')->get();

        foreach ($users as $user) {
            $user->assignRole("admin");
        }
    }
    private function officerPermission(): void
    {
        Role::create(["name" => "officer"]);
        $officerRole = Role::findByName("officer");

        $officerRole->givePermissionTo("get-all-patient");
        $officerRole->givePermissionTo("get-detail-patient");
        $officerRole->givePermissionTo("create-patient");
        $officerRole->givePermissionTo("edit-patient");
        $officerRole->givePermissionTo("delete-patient");
        $officerRole->givePermissionTo("print-patient");
        $officerRole->givePermissionTo("register-patient");
        $officerRole->givePermissionTo("get-report-patient");

        $officerRole->givePermissionTo("get-all-treatment");
        $officerRole->givePermissionTo("get-detail-treatment");
        $officerRole->givePermissionTo("create-treatment");
        $officerRole->givePermissionTo("edit-treatment");
        $officerRole->givePermissionTo("delete-treatment");
        $officerRole->givePermissionTo("print-treatment");

        $officerRole->givePermissionTo("get-all-medicine");
        $officerRole->givePermissionTo("get-detail-medicine");
        $officerRole->givePermissionTo("create-medicine");
        $officerRole->givePermissionTo("edit-medicine");
        $officerRole->givePermissionTo("delete-medicine");
        $officerRole->givePermissionTo("print-medicine");

        $officerRole->givePermissionTo("get-all-transaction-medicine");
        $officerRole->givePermissionTo("print-transaction-medicine");

        $officerRole->givePermissionTo("get-all-appointment");
        $officerRole->givePermissionTo("create-appointment");
        $officerRole->givePermissionTo("cancel-appointment");

        $officerRole->givePermissionTo("get-all-diagnosis");
        $officerRole->givePermissionTo("get-detail-diagnosis");
        $officerRole->givePermissionTo("create-diagnosis");
        $officerRole->givePermissionTo("edit-diagnosis");
        $officerRole->givePermissionTo("delete-diagnosis");
        $officerRole->givePermissionTo("print-diagnosis");

        $users = User::where('role', 'officer')->get();

        foreach ($users as $user) {
            $user->assignRole("officer");
        }
    }
    private function inventoryOfficerPermission(): void
    {

        Role::create(["name" => "inventory_officer"]);
        $inventoryOfficerRole = Role::findByName("inventory_officer");

        $inventoryOfficerRole->givePermissionTo("get-all-medicine");
        $inventoryOfficerRole->givePermissionTo("get-detail-medicine");
        $inventoryOfficerRole->givePermissionTo("create-medicine");
        $inventoryOfficerRole->givePermissionTo("edit-medicine");
        $inventoryOfficerRole->givePermissionTo("delete-medicine");
        $inventoryOfficerRole->givePermissionTo("print-medicine");

        $inventoryOfficerRole->givePermissionTo("get-all-transaction-medicine");
        $inventoryOfficerRole->givePermissionTo("create-transaction-medicine");
        $inventoryOfficerRole->givePermissionTo("print-transaction-medicine");
        $inventoryOfficerRole->givePermissionTo("get-report-transaction-medicine");

        $users = User::where('role', 'inventory_officer')->get();

        foreach ($users as $user) {
            $user->assignRole("inventory_officer");
        }
    }
    private function doctorPermission(): void
    {

        Role::create(["name" => "doctor"]);
        $doctorRole = Role::findByName("doctor");

        $doctorRole->givePermissionTo("get-all-appointment");
        $doctorRole->givePermissionTo("create-appointment");
        $doctorRole->givePermissionTo("process-appointment");
        $doctorRole->givePermissionTo("get-history-appointment");

        $doctorRole->givePermissionTo("create-diagnosis");
        $doctorRole->givePermissionTo("edit-diagnosis");
        $doctorRole->givePermissionTo("delete-diagnosis");
        $doctorRole->givePermissionTo("print-diagnosis");

        $doctorRole->givePermissionTo("get-all-medicine");
        $users = User::where('role', 'doctor')->get();

        foreach ($users as $user) {
            $user->assignRole("doctor");
        }
    }
}
