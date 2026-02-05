<?php

namespace App\Http\Controllers;

use App\DTO\LoginLogDTO;
use App\DTO\UserDTO;
use App\Repository\LoginLogRepository;
use App\Repository\UserRepository;
use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private LoginLogRepository $loginLogRepository,
        private UserRepository $userRepository,
    ) {}

    public function index()
    {
        return view("auth.login");
    }

    public function create(Request $request, AuthService $authService)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:64'],
        ]);
        
        $user = $this->userRepository->getByUsername($validated['username']);
        $isActive = $user?->isActive ?? false;

        
        if (!$user || !$isActive) {

            $loginLogDTO = new LoginLogDTO();
            $loginLogDTO->ipAddress = $request->ip();
            $loginLogDTO->usernameAttempt = $validated['username'];
            $loginLogDTO->deviceInfo = $request->header('User-Agent');

            $this->loginLogRepository->insert($loginLogDTO);
            return back()->with("failed", "Akun tidak ditemukan atau terkunci !. Silakan hubungi administrator.");
        }
        
        if ($authService->attempt($validated['username'], $validated['password'])) {
            return $this->handleSuccessLogin($request, $user);
        }

        return $this->handleFailedLogin($request, $user);

       
    }

    private function handleSuccessLogin(Request $request, UserDTO $user)
    {
        $request->session()->regenerate();
        $this->userRepository->resetFailedLoginAttempts($user->id);

        $loginLogDTO = new LoginLogDTO();
        $loginLogDTO->userId = $user->id;
        $loginLogDTO->ipAddress = $request->ip();
        $loginLogDTO->usernameAttempt = $user->username;
        $loginLogDTO->deviceInfo = $request->header('User-Agent');
        $loginLogDTO->loginAt = now();
        $loginLogDTO->isSuccess = true;

        $this->loginLogRepository->insert($loginLogDTO);
        $this->userRepository->updateLastLoginAt($user->id, now());
        return redirect()->intended('/');
    }

    private function handleFailedLogin(Request $request,UserDTO $user)
    {
        $failedAttempts = $this->userRepository->getFailedLoginAttempsById($user->id);

        if ($failedAttempts !== null) {
            $failedAttempts += 1;
            $this->userRepository->updateFailedLoginAttemptsById($user->id, $failedAttempts);

            if ($failedAttempts >= 3) {
                $this->userRepository->deactivate($user->id);
                return back()->with("failed", "Akun anda terkunci karena terlalu banyak percobaan login gagal. Silakan hubungi administrator.");
            }
        }
        $loginLogDTO = new LoginLogDTO();
        $loginLogDTO->ipAddress = $request->ip();
        $loginLogDTO->usernameAttempt = $user->username;
        $loginLogDTO->deviceInfo = $request->header('User-Agent');
        $this->loginLogRepository->insert($loginLogDTO);

        return back()->with("failed", "Login failed");
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login")->with("success", "Berhasil Logout");
    }
}