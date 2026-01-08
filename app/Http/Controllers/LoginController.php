<?php

namespace App\Http\Controllers;

use App\Service\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view("auth/login");
    }

    public function create(Request $request, AuthService $authService)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:5', 'max:20'],
            'password' => ['required', 'string', 'min:5', 'max:20'],
        ]);

        if ($authService->attempt($validated['username'], $validated['password'])) {
            return redirect("/");
        }

        return back()->with("error", "Login failed");
    }
}
