<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Mesa;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaConfirmada;
use App\Mail\ReservaCancelada;
use App\Mail\ReservaEnRevision;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class ReservaController extends Controller
{
    // Formulario público para hacer reserva
    public function create()
    {
        // Obtener todas las mesas disponibles
        $mesas = Mesa::all();
        
        // Obtener platos disponibles para pre-orden (opcional)
        $platos = Plato::where('disponible', 1)->get();
        
        return view('reservas.create', compact('mesas', 'platos'));
    }

    // Guardar la reserva
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'personas' => 'required|integer|min:1|max:12',
            'mesa_id' => 'required|exists:mesas,id',
            'notas' => 'nullable|string|max:500',
            'platos' => 'nullable|array', // Array de platos pre-ordenados
            // Pago — como acordado, solo Yape
            'metodo_pago' => 'required|string|in:yape',
            'numero_operacion' => 'required_if:metodo_pago,yape|string',
            'monto_total' => 'required|numeric|min:0',
        ]);

        // Crear la reserva
        $reserva = Reserva::create([
            'nombre_cliente' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'] ?? null,
            'fecha_reserva' => $validated['fecha'],
            'hora_reserva' => $validated['hora'],
            'numero_personas' => $validated['personas'],
            'mesa_id' => $validated['mesa_id'],
            'comentarios' => $validated['notas'] ?? null,
            'estado' => 'pendiente',
        ]);

        // Actualizar estado de la mesa
        Mesa::where('id', $validated['mesa_id'])->update(['estado' => 'reservada']);

        // Guardar platos pre-ordenados (si existen)
        if (!empty($validated['platos'])) {
            foreach ($validated['platos'] as $plato) {
                // Asumiendo que tienes una tabla pivote reserva_platos
                // Si no la tienes, puedes guardar en JSON o crear la relación
                $reserva->platos()->attach($plato['id'], [
                    'cantidad' => $plato['cantidad'],
                    'precio' => $plato['precio']
                ]);
            }
        }

        // TODO: Enviar email de confirmación
        // Mail::to($reserva->email)->send(new ReservaConfirmada($reserva));

        // Crear registro de pago en estado 'pendiente'
        // Insertar pago usando los nombres de columna que usa el modelo Pago
        $pagoCreado = false;
        try {
            DB::table('pagos')->insert([
                'venta_id' => null,
                'reserva_id' => $reserva->id,
                'metodo' => $validated['metodo_pago'],
                'numero_operacion' => $validated['numero_operacion'],
                'monto' => $validated['monto_total'],
                'fecha' => now(),
                'estado' => 'pendiente',
            ]);
            $pagoCreado = true;
        } catch (\Exception $e) {
            // Registrar error pero continuar (la reserva ya fue creada)
            Log::error('Error al crear pago para reserva ' . $reserva->id . ': ' . $e->getMessage());
            $pagoCreado = false;
        }

        // Enviar correo "en revisión" (si hay email registrado). La confirmación se enviará al confirmar el pago.
        if ($reserva->email) {
            try {
                Mail::to($reserva->email)->send(new ReservaEnRevision($reserva));
            } catch (\Exception $e) {
                Log::warning('No se pudo enviar correo (en revisión) para reserva ' . $reserva->id . ': ' . $e->getMessage());
            }
        }

        // Si la petición es AJAX/JSON, devolver resultado en JSON para que el front pueda mostrar feedback
        if ($request->wantsJson() || $request->ajax()) {
            if ($pagoCreado) {
                return response()->json([
                    'success' => true,
                    'reserva_id' => $reserva->id,
                    'message' => 'Reserva creada y pago registrado (pendiente).'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Reserva creada pero hubo un problema registrando el pago.'
            ], 500);
        }

        // Redirigir a página de confirmación para peticiones normales
        return redirect()->route('reservas.confirmacion', $reserva->id)
                        ->with('success', '¡Reserva realizada con éxito!');
    }

    // Página de confirmación (después del POST exitoso)
    public function confirmacion($id)
    {
        $reserva = Reserva::with(['mesa', 'platos'])->findOrFail($id);

    // Mapear atributos esperados por la vista (compatibilidad con vistas existentes)
    $reserva->nombre = $reserva->nombre_cliente;
    $reserva->telefono = $reserva->telefono;
    $reserva->email = $reserva->email;
    $reserva->fecha = $reserva->fecha_reserva;
    $reserva->hora = $reserva->hora_reserva;
    $reserva->personas = $reserva->numero_personas;
    $reserva->notas = $reserva->comentarios;

    // Alias por compatibilidad: la vista usa 'pedidos' mientras el modelo tiene 'platos'
    $reserva->pedidos = $reserva->platos;

        // Generar un código de confirmación si no existe (temporal, no persiste)
        if (empty($reserva->codigo_confirmacion)) {
            $reserva->codigo_confirmacion = 'R' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT);
        }

        return view('reservas.confirmacion', compact('reserva'));
    }

    // Descargar comprobante en PDF
    public function pdf($id)
    {
        $reserva = Reserva::with(['mesa', 'platos'])->findOrFail($id);

        // Asegurar alias usados por la vista PDF
        $reserva->nombre = $reserva->nombre_cliente;
        $reserva->telefono = $reserva->telefono;
        $reserva->email = $reserva->email;
        $reserva->fecha = $reserva->fecha_reserva;
        $reserva->hora = $reserva->hora_reserva;
        $reserva->personas = $reserva->numero_personas;
        $reserva->notas = $reserva->comentarios;
        if (empty($reserva->codigo_confirmacion)) {
            $reserva->codigo_confirmacion = 'R' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT);
        }

        try {
            $pdf = PDF::loadView('pdfs.reserva_comprobante', ['reserva' => $reserva]);
            return $pdf->download('reserva_' . $reserva->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generando PDF para reserva ' . $reserva->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo generar el PDF en este momento.');
        }
    }

    // Reenviar email de confirmación
    public function reenviarEmail($id)
    {
        $reserva = Reserva::findOrFail($id);

        if (empty($reserva->email)) {
            return redirect()->back()->with('error', 'No hay correo asociado a esta reserva');
        }

        try {
            Mail::to($reserva->email)->send(new ReservaConfirmada($reserva));
            return redirect()->back()->with('success', 'Correo reenviado correctamente');
        } catch (\Exception $e) {
            Log::warning('No se pudo reenviar correo para reserva ' . $reserva->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo reenviar el correo.');
        }
    }

    // Generar enlace para agregar al Google Calendar
    public function googleCalendar($id)
    {
        $reserva = Reserva::findOrFail($id);

        // FechaHora de inicio y fin (asumir 2 horas de duración)
        $start = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
        $end = (clone $start)->addHours(2);

        $dates = $start->utc()->format('Ymd\THis\Z') . '/' . $end->utc()->format('Ymd\THis\Z');

        $title = urlencode('Reserva en Nuestro Restaurante - ' . ($reserva->nombre_cliente ?? 'Cliente'));
        $details = urlencode('Reserva para ' . ($reserva->numero_personas ?? '') . ' personas. Código: ' . ($reserva->id));
        $location = urlencode('Nuestro Restaurante');

        $url = "https://www.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$dates}&details={$details}&location={$location}";

        return redirect()->away($url);
    }

    // Ver reservas (para cajero/admin)
    public function index(Request $request)
    {
        $estado = $request->input('estado');
        $buscar = $request->input('q');
        $fecha = $request->input('fecha');

        // Hoy
        $hoyQuery = Reserva::query()->whereDate('fecha_reserva', Carbon::today());
        if (!empty($estado)) {
            $hoyQuery->where('estado', $estado);
        }
        if (!empty($buscar)) {
            $hoyQuery->where(function($q) use ($buscar) {
                $q->where('nombre_cliente', 'like', "%$buscar%")
                  ->orWhere('telefono', 'like', "%$buscar%")
                  ->orWhere('id', $buscar);
            });
        }
        $reservasHoy = $hoyQuery->orderBy('hora_reserva')->get();

        // Próximas (o por fecha específica si se envía)
        $proxQuery = Reserva::query();
        if (!empty($fecha)) {
            $proxQuery->whereDate('fecha_reserva', $fecha);
        } else {
            $proxQuery->whereDate('fecha_reserva', '>', Carbon::today());
        }
        if (!empty($estado)) {
            $proxQuery->where('estado', $estado);
        }
        if (!empty($buscar)) {
            $proxQuery->where(function($q) use ($buscar) {
                $q->where('nombre_cliente', 'like', "%$buscar%")
                  ->orWhere('telefono', 'like', "%$buscar%")
                  ->orWhere('id', $buscar);
            });
        }
        $reservasProximas = $proxQuery->orderBy('fecha_reserva')->orderBy('hora_reserva')->get();

        // Mesas disponibles para asignación
        $mesasDisponibles = Mesa::query()->where('estado', 'disponible')->orderBy('numero')->get();

        return view('reservas.index', compact('reservasHoy', 'reservasProximas', 'mesasDisponibles', 'estado', 'buscar', 'fecha'));
    }

    // Confirmar reserva
    public function confirmar($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->update(['estado' => 'confirmada']);

        return redirect()->back()->with('success', 'Reserva confirmada exitosamente');
    }

    // Asignar mesa
    public function asignarMesa(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->update([
            'mesa_id' => $request->mesa_id,
            'estado' => 'confirmada'
        ]);

        Mesa::where('id', $request->mesa_id)->update(['estado' => 'reservada']);

        return redirect()->back()->with('success', 'Mesa asignada correctamente');
    }

    // Cancelar reserva
    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);
        
        if ($reserva->mesa_id) {
            Mesa::where('id', $reserva->mesa_id)->update(['estado' => 'disponible']);
        }
        
        $reserva->update(['estado' => 'cancelada']);

        return redirect()->back()->with('success', 'Reserva cancelada');
    }

    // Completar reserva
    public function completar($id)
    {
        $reserva = Reserva::findOrFail($id);
        
        if ($reserva->mesa_id) {
            Mesa::where('id', $reserva->mesa_id)->update(['estado' => 'ocupada']);
        }
        
        $reserva->update(['estado' => 'completada']);

        return redirect()->back()->with('success', 'Cliente llegó, reserva completada');
    }

    // Formulario público: Consultar mi reserva
    public function consultarForm()
    {
        return view('reservas.consultar');
    }

    // Búsqueda pública de reserva por ID o código
    public function consultarBuscar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string'
        ]);

        $input = trim($request->codigo);
        $id = null;

        if (preg_match('/^R(\d{1,})$/i', $input, $m)) {
            // Código del tipo R000123 -> extraer número
            $id = ltrim($m[1], '0');
        } elseif (ctype_digit($input)) {
            $id = (int) $input;
        }

        if (!$id) {
            return back()->with('error', 'Código o ID inválido')->withInput();
        }

        $reserva = Reserva::with(['mesa', 'platos'])->find($id);
        if (!$reserva) {
            return back()->with('error', 'No se encontró la reserva')->withInput();
        }

        // Asegurar alias esperados en vistas
        $reserva->nombre = $reserva->nombre_cliente;
        $reserva->fecha = $reserva->fecha_reserva;
        $reserva->hora = $reserva->hora_reserva;
        $reserva->personas = $reserva->numero_personas;
        $reserva->notas = $reserva->comentarios;
        $reserva->pedidos = $reserva->platos;
        if (empty($reserva->codigo_confirmacion)) {
            $reserva->codigo_confirmacion = 'R' . str_pad($reserva->id, 6, '0', STR_PAD_LEFT);
        }

        return view('reservas.consultar_resultado', compact('reserva'));
    }
}