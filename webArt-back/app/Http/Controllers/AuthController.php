<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    Auth::login($user); // Vincula user a sesión activa
    $request->session()->regenerate(); // ✅ Obligatorio para evitar múltiples cookies

    return response()->json(['message' => 'Login exitoso', 'user' => $user]);
}

    
    public function logout(Request $request)
{
    Auth::guard('web')->logout(); // Cierra la sesión
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Sesión cerrada']);
}
}

