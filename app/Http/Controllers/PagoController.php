<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservaConfirmada;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    /**
     * Update payment for a reservation (mark as paid and store payment details).
     * This controller is reservation-only; legacy 'acta' flows were removed.
     */
    public function marcarComoPagado(Request $request)
    {
        $request->validate([
            'reserva_id' => 'required|integer|exists:reservas,id',
            'metodo' => 'required|string',
            'numero_operacion' => 'nullable|string',
            'monto' => 'required|numeric',
            'estado' => 'nullable|string',
        ]);

        $reserva = Reserva::findOrFail($request->reserva_id);

        // Prefer updating an existing pago linked to the reserva to avoid legacy NOT NULL venta_id issues.
        $pago = Pago::where('reserva_id', $reserva->id)->first();

        if (! $pago) {
            // If there is no existing Pago row, instruct caller to create it via reservation flow
            return response()->json([
                'message' => 'No se encontró un registro de pago asociado a la reserva. Por favor, crear el pago desde la reserva primero.'
            ], 422);
        }

        $pago->metodo = $request->metodo;
        $pago->numero_operacion = $request->numero_operacion;
        $pago->monto = $request->monto;
        $pago->fecha = now();
        $pago->estado = $request->estado ?? 'completado';

        $pago->save();

        // Try sending confirmation email with PDF
        try {
            if (! empty($reserva->email)) {
                Mail::to($reserva->email)->send(new ReservaConfirmada($reserva));
            }
        } catch (\Exception $e) {
            Log::warning('No se pudo enviar el correo de confirmación para la reserva ' . $reserva->id . ': ' . $e->getMessage());
        }

        return response()->json(['message' => 'Pago actualizado correctamente', 'pago_id' => $pago->id]);
    }

    /**
     * Resend the reservation confirmation email (with PDF) for a given pago id.
     */
    public function reenviarComprobante($id)
    {
        $pago = Pago::findOrFail($id);

        if (! $pago->reserva) {
            return redirect()->back()->with('error', 'Pago sin reserva asociada.');
        }

        try {
            Mail::to($pago->reserva->email)->send(new ReservaConfirmada($pago->reserva));
        } catch (\Exception $e) {
            Log::error('Error al reenviar comprobante para pago ' . $pago->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo reenviar el comprobante.');
        }

        return redirect()->back()->with('success', 'Comprobante reenviado correctamente.');
    }

    /**
     * Generate or stream the PDF receipt for the reservation related to this pago.
     */
    public function pdf($id)
    {
        $pago = Pago::findOrFail($id);

        if (! $pago->reserva) {
            abort(404, 'Reserva no encontrada para este pago.');
        }

        $reserva = $pago->reserva;

        try {
            $pdf = Pdf::loadView('pdfs.reserva_comprobante', compact('reserva'));
            return $pdf->stream('reserva_' . $reserva->id . '_comprobante.pdf');
        } catch (\Exception $e) {
            Log::error('Error generando PDF para reserva ' . $reserva->id . ': ' . $e->getMessage());
            // Fallback: show plain view
            return view('pdfs.reserva_comprobante', compact('reserva'));
        }
    }

    /**
     * Minimal index for admin listing of pagos (reservation-oriented).
     */
    public function index()
    {
        $pagos = Pago::with('reserva')->orderBy('fecha', 'desc')->paginate(20);
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Dashboard: Lista de pagos asociados a reservas, con filtros.
     */
    public function reservasIndex(Request $request)
    {
        $estado = $request->input('estado'); // pendiente|confirmado|fallido
        $q = $request->input('q'); // id reserva, numero_operacion
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = Pago::query()
            ->with('reserva')
            ->whereNotNull('reserva_id');

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        if (!empty($q)) {
            $query->where(function($sub) use ($q) {
                $sub->where('numero_operacion', 'like', "%$q%")
                    ->orWhere('reserva_id', $q)
                    ->orWhere('id', $q);
            });
        }

        if (!empty($desde)) {
            $query->whereDate('fecha', '>=', $desde);
        }
        if (!empty($hasta)) {
            $query->whereDate('fecha', '<=', $hasta);
        }

        $pagos = $query->orderBy('fecha', 'desc')->paginate(20)->appends($request->query());

        return view('pagos.reservas_index', compact('pagos', 'estado', 'q', 'desde', 'hasta'));
    }

    /**
     * Actualiza el estado de un pago de reserva.
     */
    public function actualizarEstado(Request $request, $id)
    {
        $data = $request->validate([
            'estado' => 'required|in:pendiente,confirmado,fallido',
        ]);

        $pago = Pago::findOrFail($id);
        $pago->estado = $data['estado'];
        $pago->save();

        if ($pago->reserva) {
            // Si el pago fue confirmado:
            if ($pago->estado === 'confirmado') {
                // 1) Marcar la mesa como ocupada si existe
                // if ($pago->reserva->mesa_id) {
                //     Mesa::where('id', $pago->reserva->mesa_id)->update(['estado' => 'ocupada']);
                // }
                // 2) Marcar la reserva como confirmada
                $pago->reserva->update(['estado' => 'confirmada']);

                if ($pago->reserva->mesa_id) {
                    Mesa::where('id', $pago->reserva->mesa_id)
                        ->update(['estado' => 'reservada']);
                }
                
                // 3) Enviar correo de confirmación si hay email
                try {
                    if (! empty($pago->reserva->email)) {
                        Mail::to($pago->reserva->email)->send(new ReservaConfirmada($pago->reserva));
                    }
                } catch (\Exception $e) {
                    Log::warning('No se pudo enviar el correo de confirmación para la reserva ' . $pago->reserva->id . ': ' . $e->getMessage());
                }
            }

            // Si el pago fue fallido: liberar mesa si tuviera una
            if ($pago->estado === 'fallido') {
                // if ($pago->reserva->mesa_id) {
                //     Mesa::where('id', $pago->reserva->mesa_id)->update(['estado' => 'disponible']);
                // }

                $pago->reserva->update([
                    'estado' => 'cancelada'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Estado del pago actualizado');
    }
}
