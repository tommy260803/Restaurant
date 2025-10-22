<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function verificaLogin(Request $request)
    {
        \Log::info("👉 Ingresó al método login");

        $credentials = [
            'email_mi_acta' => $request->email_mi_acta,
            'contrasena' => $request->password,
            'estado' => 1,
        ];

        \Log::info('🔐 Intentando autenticación con: ', $credentials);

        if (Auth::attempt([
            'email_mi_acta' => $request->email_mi_acta,
            'password' => $request->password,
            'estado' => 1,
        ])) {
            $request->session()->regenerate();

            // ✅ Marca que es el primer login para mostrar el mensaje de bienvenida
            session(['first_login' => true]);

            \Log::info("✅ LOGIN EXITOSO para: {$request->email_mi_acta}");
            return redirect()->intended('/home');
        }

        \Log::warning("❌ LOGIN FALLIDO para: {$request->email_mi_acta}");
        return back()->withErrors([
            'email' => '⚠️ Correo o contraseña incorrectos o cuenta inactiva.',
        ]);
    }
}