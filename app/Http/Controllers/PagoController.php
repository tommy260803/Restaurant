<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Persona;
use App\Models\Tarifa;
use App\Models\ActaNacimiento;
use App\Models\ActaMatrimonio;
use App\Models\ActaDefuncion;
use App\Models\RecienNacido;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarComprobanteAdjunto;
use App\Mail\PagoFallido;

class PagoController extends Controller
{

    public function index()
    {
        $pagos = Pago::orderBy('fecha_pago', 'desc')
                    ->paginate(10);

        return view('pagos.index', compact('pagos'));
    }


    public function validarPago($id)
    {
        $pago = Pago::findOrFail($id); 
        return view('pagos.validarPago', compact('pago'));
    }

    public function update(Request $request, $id)
    {
        $pajo = Pago::findOrFail($id);
        $pajo->estado = $request->input('estado');
        $pajo->save();

        // Validamos que haya correo registrado
        if ($pajo->Correo) {
            if ($pajo->estado === 'completado') {
                // Buscar el acta correspondiente
                $acta = match ($pajo->tipo_acta) {
                    'acta_nacimiento' => \App\Models\ActaNacimiento::find($pajo->id_acta),
                    'acta_matrimonio' => \App\Models\ActaMatrimonio::find($pajo->id_acta),
                    'acta_defuncion'  => \App\Models\ActaDefuncion::find($pajo->id_acta),
                    default => null,
                };

                if ($acta && $acta->ruta_doc_generado) {
                    $rutaRelativa = $acta->ruta_doc_generado;

                    if (Storage::disk('public')->exists($rutaRelativa)) {
                        \Log::info("✅ Enviando comprobante a {$pajo->Correo} con PDF: {$rutaRelativa}");
                        Mail::to($pajo->Correo)->send(new EnviarComprobanteAdjunto($pajo, $rutaRelativa));
                    } else {
                        \Log::warning("⚠ No se encontró el PDF en: {$rutaRelativa}");
                    }
                } else {
                    \Log::warning("⚠ Acta no encontrada o sin documento generado.");
                }

            } elseif ($pajo->estado === 'fallido') {
                // Estado fallido, enviar mensaje sin adjunto
                \Log::info("❌ Enviando notificación de pago fallido a: {$pajo->Correo}");
                Mail::to($pajo->Correo)->send(new PagoFallido($pajo));
            }
        }

        return redirect()->route('pagos.index')->with('mensaje', 'Estado del pago actualizado. Se notificó al usuario si corresponde.');
    }


    public function vistaConfirmarPago(Request $request)
    {
        $id_acta = $request->query('id_acta');
        $tipo_acta = $request->query('tipo_acta');
        $monto = $request->query('monto');
        $num_transaccion = $request->query('numero_transaccion');

        session([
            'pago.id_acta' => $id_acta,
            'pago.tipo_acta' => $tipo_acta,
        ]);

        return view('pagos.confirmarPago', compact('id_acta', 'tipo_acta', 'monto', 'num_transaccion'));
    }

    public function registrarActa(Request $request)
    {
        return view('pagos.registrarActa');
    }

    public function guardarDatos(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
            'correo' => 'required|email',
        ]);

        session([
            'dni' => $request->dni,
            'correo' => $request->correo,
        ]);

        return redirect()->route('pagos.buscarActa');
    }

    public function buscarActa() {
        return view('pagos.buscarActa');
    }

    public function obtenerActasPorTipo($tipo)
    {
        switch ($tipo) {
            case 'acta_nacimiento':
                $actas = ActaNacimiento::with('recienNacido')->get()->map(function ($a) {
                    $fecha = Carbon::parse($a->fecha_registro)->format('d/m/Y');
                    $nombre = optional($a->recienNacido)->nombre 
                            . ' ' . optional($a->recienNacido)->apellido_paterno 
                            . ' ' . optional($a->recienNacido)->apellido_materno 
                            ?? 'Recien Nacido #' . $a->id_recien_nacido;
                    return [
                        'id' => $a->id_acta_nacimiento,
                        'nombre' => "Acta Nacimiento - $fecha - $nombre"
                    ];
                });
                break;

            case 'acta_matrimonio':
                $actas = ActaMatrimonio::with(['conyuge1', 'conyuge2'])->get()->map(function ($a) {
                    $fecha = Carbon::parse($a->fecha_matrimonio)->format('d/m/Y');
                    $c1 = optional($a->conyuge1)->nombres ?? 'Cónyuge 1';
                    $c2 = optional($a->conyuge2)->nombres ?? 'Cónyuge 2';
                    return [
                        'id' => $a->id_acta_matrimonio,
                        'nombre' => "Acta Matrimonio - $fecha - $c1 y $c2"
                    ];
                });
                break;

            case 'acta_defuncion':
                $actas = ActaDefuncion::with('fallecido')->get()->map(function ($a) {
                    $fecha = Carbon::parse($a->fecha_defuncion)->format('d/m/Y');
                    $nombre = optional($a->fallecido)->nombres 
                            . ' ' . optional($a->fallecido)->apellido_paterno 
                            . ' ' . optional($a->fallecido)->apellido_materno 
                            ?? 'Fallecido #' . $a->dni_fallecido;
                    return [
                        'id' => $a->id_acta_defuncion,
                        'nombre' => "Acta Defunción - $fecha - $nombre"
                    ];
                });
                break;

            default:
                $actas = collect();
        }

        return response()->json($actas);
    }

    public function obtenerMontoPorTipo($tipo)
    {
        $tarifa = Tarifa::where('tipo_acta', $tipo)->first();
        return response()->json(['monto' => $tarifa->monto ?? 0]);
    }

    public function confirmar($id)
    {
        $pago = Pago::findOrFail($id);

        $pago->estado = 'pagado';
        $pago->save();

        return redirect()->route('pagos.index')->with('success', 'Pago confirmado correctamente.');
    }

    public function buscarActaNacimiento(Request $request)
    {
        $datos = $request->only(['apellido_paterno', 'apellido_materno', 'nombre', 'fecha_nacimiento']);

        $recienNacido = RecienNacido::where('apellido_paterno', $datos['apellido_paterno'])
            ->where('apellido_materno', $datos['apellido_materno'])
            ->where('nombre', $datos['nombre'])
            ->where('fecha_nacimiento', $datos['fecha_nacimiento'])
            ->first();

        if (!$recienNacido) {
            $request->merge(['tipo_acta' => 'nacimiento']);
            return back()->with('error', 'No se encontró al recién nacido.')->withInput();
        }

        $acta = ActaNacimiento::where('id_recien_nacido', $recienNacido->id_recien_nacido)->first();

        if (!$acta) {
            $request->merge(['tipo_acta' => 'nacimiento']);
            return back()->with('error', 'No se encontró el acta de nacimiento.')->withInput();
        }

        return view('pagos.tipoActa', [
            'id_acta' => $acta->id_acta_nacimiento,
            'fecha_registro' => $acta->fecha_registro,
            'tipo' => 'acta_nacimiento'
        ]);
    }

    public function buscarActaDefuncion(Request $request)
    {
        $request->validate([
            'fecha_defuncion' => 'required|date',
            'nombre' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'required|string',
        ]);

        // Buscar persona fallecida por nombre completo
        $persona = Persona::where('nombres', 'like', '%' . $request->nombre . '%')
            ->where('apellido_paterno', 'like', '%' . $request->apellido_paterno . '%')
            ->where('apellido_materno', 'like', '%' . $request->apellido_materno . '%')
            ->first();

        if (!$persona) {
            $request->merge(['tipo_acta' => 'defuncion']);
            return back()->with('error', 'No se encontró a la persona fallecida.')->withInput();
        }

        // Buscar acta de defunción relacionada a esa persona
        $acta = ActaDefuncion::where('fecha_defuncion', $request->fecha_defuncion)
            ->where('dni_fallecido', $persona->dni)
            ->first();

        if (!$acta) {
            $request->merge(['tipo_acta' => 'defuncion']);
            return back()->with('error', 'No se encontró el acta de defunción asociada.')->withInput();
        }

        return view('pagos.tipoActa', [
            'id_acta' => $acta->id_acta_defuncion,
            'fecha_registro' => $acta->fecha_registro,
            'tipo' => 'acta_defuncion'
        ]);
    }

    public function buscarActaMatrimonio(Request $request)
    {
        $request->validate([
            'fecha_matrimonio' => 'required|date',
            'apellido_paterno_c1' => 'required|string',
            'apellido_materno_c1' => 'required|string',
            'apellido_paterno_c2' => 'required|string',
            'apellido_materno_c2' => 'required|string',
        ]);

        // Buscar cónyuges
        $conyuge1 = Persona::where('apellido_paterno', 'like', '%' . $request->apellido_paterno_c1 . '%')
            ->where('apellido_materno', 'like', '%' . $request->apellido_materno_c1 . '%')
            ->first();

        $conyuge2 = Persona::where('apellido_paterno', 'like', '%' . $request->apellido_paterno_c2 . '%')
            ->where('apellido_materno', 'like', '%' . $request->apellido_materno_c2 . '%')
            ->first();

        if (!$conyuge1 || !$conyuge2) {
            $request->merge(['tipo_acta' => 'matrimonio']);
            return back()->with('error', 'Uno o ambos cónyuges no se encontraron.')->withInput();
        }

        // Buscar acta con ambos cónyuges (en cualquier orden)
        $acta = ActaMatrimonio::where('fecha_matrimonio', $request->fecha_matrimonio)
            ->where(function ($query) use ($conyuge1, $conyuge2) {
                $query->where([
                    ['dni_conyuge1', $conyuge1->dni],
                    ['dni_conyuge2', $conyuge2->dni]
                ])->orWhere([
                    ['dni_conyuge1', $conyuge2->dni],
                    ['dni_conyuge2', $conyuge1->dni]
                ]);
            })
            ->first();

        if (!$acta) {
            $request->merge(['tipo_acta' => 'matrimonio']);
            return back()->with('error', 'No se encontró el acta de matrimonio.')->withInput();
        }

        return view('pagos.tipoActa', [
            'id_acta' => $acta->id_acta_matrimonio,
            'fecha_registro' => $acta->fecha_registro,
            'tipo' => 'acta_matrimonio'
        ]);
    }

    public function pagoActa($id, Request $request)
    {
        $tipo = $request->query('tipo');

        $tarifa = Tarifa::where('tipo_acta', $tipo)->first();

        return view('pagos.pagoActa', [
            'id_acta' => $id,
            'tipo_acta' => $tipo,
            'tarifa' => $tarifa,
        ]);
    }  

    public function reportes(Request $request)
    {
        $pagos = Pago::orderBy('fecha_pago', 'desc')->paginate(10);
        $totalPagos = Pago::sum('monto');
        $pagosCompletados = Pago::count();
        $totalMes = Pago::whereMonth('fecha_pago', Carbon::now()->month)
                       ->whereYear('fecha_pago', Carbon::now()->year)
                       ->sum('monto');
        
        return view('pagos.reportes', compact(
            'pagos',
            'totalPagos',
            'pagosCompletados',
            'totalMes'
        ));
    }

    public function confirmarPago(Request $request)
    {
        $request->validate([
            'id_acta' => 'required',
            'tipo_acta' => 'required',
            'monto' => 'required|numeric',
            'numero_transaccion' => 'required|min:6',
        ]);

        $pago = new Pago();
        $pago->id_acta = $request->id_acta;
        $pago->tipo_acta = $request->tipo_acta;
        $pago->monto = $request->monto;
        $pago->num_transaccion = $request->numero_transaccion;
        $pago->metodo_pago = $request->metodo_pago;
        $pago->estado = 'pendiente';
        $pago->fecha_pago = now();
        $pago->Correo = session('correo');
        $pago->DNI = session('dni'); 

        $pago->save();

        return view('pagos.confirmarPago', [
            'id_acta' => $pago->id_acta,
            'tipo_acta' => $pago->tipo_acta,
            'monto' => $pago->monto,
            'num_transaccion' => $pago->num_transaccion,
            'estado' => $pago->estado,
        ]);
    }

    public function guardarPago(Request $request)
    {
        return redirect()->route('login')->with('success', 'Pago guardado correctamente.');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }

}
