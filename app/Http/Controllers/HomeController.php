<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // Este middleware asegura que solo usuarios autenticados accedan
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Sesión inválida. Por favor, vuelve a iniciar sesión.');
        }

        $firstLogin = session('first_login');

        session()->forget('first_login');
        
        return view('layouts.home', compact('usuario', 'firstLogin'));
    }
}