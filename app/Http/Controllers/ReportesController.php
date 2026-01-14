<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesController extends Controller
{
    // Vista principal de reportes
    public function index()
    {
        return view('reportes.index');
    }

    // Pagos por día (últimos 30 días)
    public function pagosPorDia()
    {
        $hoy = Carbon::today();
        $inicio = $hoy->copy()->subDays(29);

        $pagos = Pago::select(DB::raw("DATE(fecha) as fecha"), DB::raw('SUM(monto) as total'))
            ->where('fecha', '>=', $inicio)
            ->where('estado', 'confirmado')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->pluck('total', 'fecha');

        // Completar días sin datos
        $series = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $inicio->copy()->addDays($i)->toDateString();
            $series[$day] = isset($pagos[$day]) ? (float)$pagos[$day] : 0.0;
        }

        return response()->json(['labels' => array_keys($series), 'data' => array_values($series)]);
    }

    // Pagos por método
    public function pagosPorMetodo()
    {
        $data = Pago::select('metodo', DB::raw('SUM(monto) as total'))
            ->where('estado', 'confirmado')
            ->groupBy('metodo')
            ->get();

        return response()->json(['data' => $data]);
    }

    // Reservas por día (últimos 30 días)
    public function reservasPorDia()
    {
        $hoy = Carbon::today();
        $inicio = $hoy->copy()->subDays(29);

        $reservas = Reserva::select(DB::raw("DATE(fecha_reserva) as fecha"), DB::raw('COUNT(*) as total'))
            ->whereDate('fecha_reserva', '>=', $inicio)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->pluck('total', 'fecha');

        $series = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $inicio->copy()->addDays($i)->toDateString();
            $series[$day] = isset($reservas[$day]) ? (int)$reservas[$day] : 0;
        }

        return response()->json(['labels' => array_keys($series), 'data' => array_values($series)]);
    }

    // Top clientes por monto pagado (últimos 12 meses)
    public function topClientes()
    {
        $data = Cliente::select('cliente.idCliente', DB::raw("CONCAT(cliente.nombre,' ',cliente.apellidoPaterno,' ',COALESCE(cliente.apellidoMaterno,'')) as nombre"), DB::raw('SUM(pagos.monto) as total'))
            ->join('pagos', 'cliente.idCliente', '=', 'pagos.cliente_id')
            ->where('pagos.estado', 'confirmado')
            ->groupBy('cliente.idCliente')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return response()->json(['data' => $data]);
    }
}
