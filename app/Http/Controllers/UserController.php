<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Helper\UserRoleHelper;
use App\Repository\UserRepository;
use App\Type\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository
    ) {}


    public function index()
    {
        return view("dashboard.user.home", [
            "users" => $this->userRepository->getAll()
        ]);
    }

    public function create()
    {
        return view("dashboard.user.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
            'confirm_password' => [
                'required',
                'same:password',
            ],
            'fullname' => [
                'required',
                'string',
                'max:100',
            ],
            'role' => [
                'required',
                Rule::in([UserRole::ADMIN->value, UserRole::OFFICER->value, UserRole::DOCTOR->value]),
            ],
        ], [
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'confirm_password.same' => 'Konfirmasi password tidak sama',
            'role.in' => 'Role tidak valid',
        ]);

        $userCreateDto = new UserDTO();
        $userCreateDto->username = $validated["username"];
        $userCreateDto->password = $validated["password"];
        $userCreateDto->fullname = $validated["fullname"];
        $userCreateDto->email = $validated["email"];
        $userCreateDto->role = UserRoleHelper::stringToUserRole($validated["role"]);

        if (!$this->userRepository->insert($userCreateDto)) {
            return redirect()->route("user.index")->with("failed", "Gagal! User gagal ditambah");
        }

        return redirect()->route("user.index")->with("success", "Sukses! User berhasil ditambah");
    }

    public function edit($id)
    {
        $user = $this->userRepository->getById($id);

        if (!$user) {
            abort(404);
        }

        return view("dashboard.user.edit", [
            "user" => $user
        ]);
    }

    public function editAction($id, Request $request)
    {
        $validated = $request->validate([
            'fullname' => [
                'required',
                'string',
                'max:100',
            ],
            'role' => [
                'required',
                Rule::in([UserRole::ADMIN->value, UserRole::OFFICER->value, UserRole::DOCTOR->value]),
            ],
        ], [
            'role.in' => 'Role tidak valid',
        ]);

        $userDto = new UserDTO();
        $userDto->id = $id;
        $userDto->role = UserRoleHelper::stringToUserRole($validated["role"]);
        $userDto->fullname = $validated["fullname"];

        if (!$this->userRepository->edit($id, $userDto)) {
            return back()->withInput();
        }

        return redirect()->route("user.index")->with("success", "Sukses! Berhasil edit user");
    }

    public function destroy($id)
    {
        $user = $this->userRepository->getById($id);

        if (!$user) {
            abort(404);
        }

        if (!$this->userRepository->delete($id)) {
            return redirect()->route("user.index")->with("failed", "Failed! Data gagal dihapus");
        }

        return redirect()->route("user.index")->with("success", "Sukses! Data berhasil dihapus");
    }
}
