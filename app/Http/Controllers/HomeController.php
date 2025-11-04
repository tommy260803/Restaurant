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

    // ========================================
    // DASHBOARDS POR ROL
    // ========================================

    /**
     * Dashboard del Administrador
     */
    public function adminDashboard()
    {
        $usuario = Auth::user();
        
        // Aquí puedes agregar estadísticas específicas para el administrador
        // Ejemplo: total de usuarios, ventas del mes, etc.
        
        return view('dashboards.admin', compact('usuario'));
    }

    /**
     * Dashboard del Cocinero
     */
    public function cocinaDashboard()
    {
        $usuario = Auth::user();
        
        // Aquí puedes agregar pedidos pendientes, en preparación, etc.
        // Ejemplo: $pedidosPendientes = Pedido::where('estado', 'pendiente')->get();
        
        return view('dashboards.cocina', compact('usuario'));
    }

    /**
     * Dashboard del Almacenero
     */
    public function almacenDashboard()
    {
        $usuario = Auth::user();
        
        // Aquí puedes agregar inventario, alertas de stock bajo, compras, etc.
        // Ejemplo: $ingredientesBajos = Ingrediente::where('stock', '<', 'stock_minimo')->get();
        
        return view('dashboards.almacen', compact('usuario'));
    }

    /**
     * Dashboard del Cajero
     */
    public function cajaDashboard()
    {
        $usuario = Auth::user();
        
        // Aquí puedes agregar ventas del día, caja actual, métodos de pago, etc.
        // Ejemplo: $ventasHoy = Venta::whereDate('created_at', today())->get();
        
        return view('dashboards.caja', compact('usuario'));
    }
}