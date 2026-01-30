<?php

namespace App\Http\Controllers;

use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}
    public function index()
    {
        return view("auth.login");
    }

    public function create(Request $request, AuthService $authService)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:20'],
            'password' => ['required', 'string', 'min:5', 'max:20'],
        ]);

        if ($authService->attempt($validated['username'], $validated['password'])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

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
