<?php

namespace App\Http\Controllers\Actas;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\ActaMatrimonio;
use App\Models\Alcalde;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\Region;
use App\Models\Folio;
use App\Models\Libro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ActaMatrimonioController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $matrimonios = ActaMatrimonio::when($buscarpor, function($query, $buscarpor) {
                $query->where(function($q) use ($buscarpor) {
                    $q->where('dni_conyuge1', 'like', "%$buscarpor%")
                      ->orWhere('dni_conyuge2', 'like', "%$buscarpor%");
                });
            })
            ->orderByDesc('id_acta_matrimonio')
            ->paginate(10);

        return view('mantenedor.matrimonio.index', compact('matrimonios', 'buscarpor'));
    }

    public function create()
    {
        $alcaldes = Alcalde::with('persona')->get();
        $usuarios = Usuario::all();
        $personas = Persona::all();
        $regiones = Region::all();
        $provincias = Provincia::all();
        $distritos = Distrito::all();
        $libros = Libro::all();
        $folios = Folio::all();

        // Alcalde vigente (puedes ajustar la lógica según tu modelo)
        $alcaldeVigente = Alcalde::with('persona')->orderByDesc('id_alcalde')->first();

        // Usuario logueado
        $usuarioLogueado = Auth::user();

        return view('mantenedor.matrimonio.create', compact(
            'alcaldes', 'usuarios', 'personas', 'regiones', 'provincias', 'distritos', 'libros', 'folios',
            'alcaldeVigente', 'usuarioLogueado'
        ));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'id_folio' => 'required',
        'dni_conyuge1' => 'required|exists:persona,dni',
        'dni_conyuge2' => 'required|exists:persona,dni',
        'fecha_matrimonio' => 'required|date',
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'id_alcalde' => 'required|exists:alcalde,id_alcalde',
        'fecha_registro' => 'required|date',
        'regimen_matrimonial' => 'required',
        'id_distrito_mat' => 'required|exists:distrito,id_distrito',
        'ruta_archivo_pdf' => 'nullable|file|mimes:pdf|max:20480',
    ]);

    // Manejo del archivo PDF subido manualmente (si existe)
    if ($request->hasFile('ruta_archivo_pdf')) {
        $file = $request->file('ruta_archivo_pdf');
        $ruta = $file->store('subidos/matrimonios', 'public');
        $data['ruta_archivo_pdf'] = $ruta;
    }

    $data['estado'] = 'activo';
    
    // Crear el registro
    $matrimonio = ActaMatrimonio::create($data);
    
    // Generar PDF automáticamente después de crear el registro
    try {
        $this->generarPDFAutomatico($matrimonio->id_acta_matrimonio);
    } catch (\Exception $e) {
        // Log del error pero no detener el proceso
        \Log::error('Error al generar PDF automático: ' . $e->getMessage());
    }

    return redirect()->route('matrimonio.index')->with('datos', 'Registro de matrimonio guardado correctamente');
}


/**
 * Generar PDF automáticamente y guardarlo en el servidor
 */
private function generarPDFAutomatico($id_matrimonio)
{
    // Cargar el matrimonio con todas sus relaciones
    $matrimonio = ActaMatrimonio::with([
        'folio.libro',
        'distrito.provincia.region',
        'conyuge1',
        'conyuge2',
        'usuario',
        'alcalde.persona'
    ])->where('id_acta_matrimonio', $id_matrimonio)->firstOrFail();

    // Generar el PDF usando la vista
    $pdf = Pdf::loadView('mantenedor.matrimonio.exportarPDF', compact('matrimonio'));
    
    // Definir la ruta donde se guardará el PDF generado automáticamente
    $rutaDirectorio = 'documentos/matrimonios';
    $nombreArchivo = 'acta_matrimonio_' . $matrimonio->id_acta_matrimonio . '_' . date('YmdHis') . '.pdf';
    $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivo;
    
    // Crear el directorio si no existe
    if (!Storage::disk('public')->exists($rutaDirectorio)) {
        Storage::disk('public')->makeDirectory($rutaDirectorio);
    }
    
    // Guardar el PDF en el storage
    Storage::disk('public')->put($rutaCompleta, $pdf->output());
    
    // Actualizar el registro con la ruta del PDF generado en el campo ruta_doc_generado
    $matrimonio->update(['ruta_doc_generado' => $rutaCompleta]);
    
    return $rutaCompleta;
}

    public function edit($id)
    {
        $matrimonio = ActaMatrimonio::with(['conyuge1', 'conyuge2', 'usuario', 'alcalde.persona'])
            ->where('id_acta_matrimonio', $id)->firstOrFail();
        $alcaldes = Alcalde::with('persona')->get();
        $usuarios = Usuario::all();
        $personas = Persona::all();
        $regiones = Region::all();
        $provincias = Provincia::all();
        $distritos = Distrito::all();
        $libros = Libro::all();
        $folios = Folio::all();
        $usuario = session('usuario');
        return view('mantenedor.matrimonio.edit', compact('matrimonio', 'alcaldes', 'usuarios', 'personas', 'regiones', 'provincias', 'distritos', 'libros', 'folios', 'usuario'));
    }

    public function update(Request $request, $id)
{
    $data = $request->validate([
        'id_folio' => 'required',
        'dni_conyuge1' => 'required|exists:persona,dni',
        'dni_conyuge2' => 'required|exists:persona,dni',
        'fecha_matrimonio' => 'required|date',
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'id_alcalde' => 'required|exists:alcalde,id_alcalde',
        'fecha_registro' => 'required|date',
        'regimen_matrimonial' => 'required',
        'id_distrito_mat' => 'required|exists:distrito,id_distrito',
        'ruta_archivo_pdf' => 'nullable|file|mimes:pdf|max:20480',
    ]);

    $matrimonio = ActaMatrimonio::where('id_acta_matrimonio', $id)->firstOrFail();

    if ($request->hasFile('ruta_archivo_pdf')) {
        if ($matrimonio->ruta_archivo_pdf) {
            Storage::disk('public')->delete($matrimonio->ruta_archivo_pdf);
        }
        $file = $request->file('ruta_archivo_pdf');
        $ruta = $file->store('subidos/matrimonios', 'public');
        $data['ruta_archivo_pdf'] = $ruta;
    }

    $matrimonio->update($data);
    $usuario = session('usuario');
    return redirect()->route('matrimonio.index')->with('datos', 'Registro de matrimonio actualizado correctamente');
}
    public function confirmar($id)
    {
        $matrimonio = ActaMatrimonio::with(['conyuge1', 'conyuge2', 'usuario', 'alcalde.persona'])
            ->where('id_acta_matrimonio', $id)->firstOrFail();
        $usuario = session('usuario');
        return view('mantenedor.matrimonio.confirmar', compact('matrimonio', 'usuario'));
    }

    public function destroy($id)
    {
        $matrimonio = ActaMatrimonio::where('id_acta_matrimonio', $id)->firstOrFail();
        $matrimonio->estado = 'inactivo'; // ENUM: minúscula
        $matrimonio->save();

        $usuario = session('usuario');
        return redirect()->route('matrimonio.index')->with('datos', 'Registro de matrimonio marcado como inactivo');
    }

    public function show($id)
    {
        $matrimonio = ActaMatrimonio::with(['conyuge1', 'conyuge2', 'usuario', 'alcalde.persona'])
            ->where('id_acta_matrimonio', $id)->firstOrFail();
        $usuario = session('usuario');
        return view('mantenedor.matrimonio.show', compact('matrimonio', 'usuario'));
    }


    public function exportarPDF($id)
    {
        $matrimonio = ActaMatrimonio::with([
            'folio.libro',
            'distrito.provincia.region',
            'conyuge1',
            'conyuge2',
            'usuario',
            'alcalde.persona'
        ])
        ->where('id_acta_matrimonio', $id)->firstOrFail();

        $pdf = Pdf::loadView('mantenedor.matrimonio.exportarPDF', compact('matrimonio'));
        return $pdf->stream('matrimonio_' . $matrimonio->id_acta_matrimonio . '.pdf');
    }

    public function exportarPDFMasivo()
    {
        $matrimonios = ActaMatrimonio::with([
            'folio.libro',
            'distrito.provincia.region',
            'conyuge1',
            'conyuge2',
            'usuario',
            'alcalde.persona'
        ])->get();

        $pdf = Pdf::loadView('mantenedor.matrimonio.exportarPDFMasivo', compact('matrimonios'));
        return $pdf->stream('reporte_matrimonios.pdf');
    }

    public function exportarVista()
    {
        $matrimonios = ActaMatrimonio::with([
            'folio.libro',
            'distrito.provincia.region',
            'conyuge1',
            'conyuge2',
            'usuario',
            'alcalde.persona'
        ])->get();

        return view('mantenedor.matrimonio.exportar', compact('matrimonios'));
    }

    public function getProvincias($id_region)
    {
        try {
            return response()->json(Provincia::where('id_region', $id_region)->orderBy('nombre')->get());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar provincias'], 500);
        }
    }

    public function getDistritos($id_provincia)
    {
        try {
            return response()->json(Distrito::where('id_provincia', $id_provincia)->orderBy('nombre')->get());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar distritos'], 500);
        }
    }

    public function getFolios($libroId)
    {
        try {
            return response()->json(Folio::where('id_libro', $libroId)->orderBy('numero_folio')->get());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar folios'], 500);
        }
    }

    public function crearFolio(Request $request)
    {
        try {
            $request->validate(['id_libro' => 'required|integer|exists:libro,id_libro']);
            DB::beginTransaction();

            $folio = Folio::create([
                'id_folio' => (DB::table('folio')->max('id_folio') ?? 0) + 1,
                'numero_folio' => (DB::table('folio')->where('id_libro', $request->id_libro)->max('numero_folio') ?? 0) + 1,
                'id_libro' => $request->id_libro
            ]);

            $folio->load('libro');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Folio #{$folio->numero_folio} creado exitosamente para el libro #{$folio->libro->numero_libro}",
                'folio' => ['id_folio' => $folio->id_folio, 'numero_folio' => $folio->numero_folio, 'libro' => ['numero_libro' => $folio->libro->numero_libro ?? 'N/A']]
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al crear el folio: ' . $e->getMessage()], 500);
        }
    }

    public function getSiguienteNumeroFolio($libroId)
    {
        try {
            return response()->json(['success' => true, 'siguienteNumero' => (Folio::where('id_libro', $libroId)->max('numero_folio') ?? 0) + 1]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener siguiente número: ' . $e->getMessage()], 500);
        }
    }

    public function buscarPersonas(Request $request)
    {
        $q = $request->input('q');
        $personas = Persona::where('nombres', 'like', "%$q%")
            ->orWhere('apellido_paterno', 'like', "%$q%")
            ->orWhere('apellido_materno', 'like', "%$q%")
            ->orWhere('dni', 'like', "%$q%")
            ->limit(20)
            ->get();

        return response()->json($personas);
    }
}