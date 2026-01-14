<?php

namespace App\Http\Controllers\Actas;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\DocumentoProveedor;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProveedorExport;
use PDF;

class ProveedorController extends Controller
{
    // 1. Lista de proveedores con búsqueda y filtro por estado
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado');

        $proveedores = Proveedor::when($buscar, function($q) use ($buscar) {
                $q->where(function($query) use ($buscar) {
                    $query->where('nombre', 'like', "%$buscar%")
                          ->orWhere('apellidoPaterno', 'like', "%$buscar%")
                          ->orWhere('apellidoMaterno', 'like', "%$buscar%")
                          ->orWhere('email', 'like', "%$buscar%")
                          ->orWhere('rucProveedor', 'like', "%$buscar%");
                });
            })
            ->when($estado, function($q) use ($estado) {
                $q->where('estado', $estado);
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('mantenedor.proveedor.index', compact('proveedores', 'buscar', 'estado'));
    }

    // 2. Formulario de registro
    public function create()
    {
        return view('mantenedor.proveedor.create');
    }

    // 3. Guardar proveedor con validación y documentación adjunta
    public function store(Request $request)
    {
        // Validaciones dinámicas según el tipo de persona
        $rules = [
            'tipo_persona' => 'required|in:natural,juridica',
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:proveedor,email',
            'documentos.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:2048',
        ];

        // Si es persona natural, apellidos son obligatorios y RUC opcional
        if ($request->tipo_persona === 'natural') {
            $rules['apellidoPaterno'] = 'required|string|max:100';
            $rules['apellidoMaterno'] = 'nullable|string|max:100';
            $rules['rucProveedor'] = 'nullable|string|max:11|unique:proveedor,rucProveedor';
        } else {
            // Si es persona jurídica, RUC es obligatorio y apellidos opcionales
            $rules['apellidoPaterno'] = 'nullable|string|max:100';
            $rules['apellidoMaterno'] = 'nullable|string|max:100';
            $rules['rucProveedor'] = 'required|string|max:11|unique:proveedor,rucProveedor';
        }

        $data = $request->validate($rules);

        // Preparar datos para insertar
        $proveedorData = [
            'nombre' => $data['nombre'],
            'apellidoPaterno' => $data['apellidoPaterno'] ?? null,
            'apellidoMaterno' => $data['apellidoMaterno'] ?? null,
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'email' => $data['email'],
            'rucProveedor' => $data['rucProveedor'] ?? null,
            'estado' => 'activo', // Valor por defecto
            'calificacion' => 0,
            'puntualidad' => 0,
            'calidad' => 0,
            'precio' => 0,
            'incumplimientos' => 0,
        ];

        $proveedor = Proveedor::create($proveedorData);

        // Guardar documentos adjuntos
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('proveedor/documentos/' . $proveedor->idProveedor, 'public');
                
                DocumentoProveedor::create([
                    'idProveedor' => $proveedor->idProveedor,
                    'archivo' => $path,
                    'nombre_original' => $originalName,
                    'tipo' => $file->getClientOriginalExtension(),
                    'tamano' => $file->getSize(),
                    'fecha_subida' => now(),
                ]);
            }
        }

        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor registrado correctamente');
    }

    // 4. Mostrar proveedor individual
    public function show($id)
    {
        $proveedor = Proveedor::with('documentos', 'compras')->findOrFail($id);
        return view('mantenedor.proveedor.show', compact('proveedor'));
    }

    // 5. Editar proveedor
    public function edit($id)
    {
        $proveedor = Proveedor::with('documentos')->findOrFail($id);
        return view('mantenedor.proveedor.edit', compact('proveedor'));
    }

    // 6. Actualizar datos del proveedor
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        
        // Validaciones dinámicas según el tipo de persona
        $rules = [
            'tipo_persona' => 'required|in:natural,juridica',
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:proveedor,email,' . $id . ',idProveedor',
            'estado' => 'nullable|in:activo,inactivo,bloqueado',
            'documentos.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:2048',
        ];

        // Si es persona natural, apellidos son obligatorios y RUC opcional
        if ($request->tipo_persona === 'natural') {
            $rules['apellidoPaterno'] = 'required|string|max:100';
            $rules['apellidoMaterno'] = 'nullable|string|max:100';
            $rules['rucProveedor'] = 'nullable|string|max:11|unique:proveedor,rucProveedor,' . $id . ',idProveedor';
        } else {
            // Si es persona jurídica, RUC es obligatorio y apellidos opcionales
            $rules['apellidoPaterno'] = 'nullable|string|max:100';
            $rules['apellidoMaterno'] = 'nullable|string|max:100';
            $rules['rucProveedor'] = 'required|string|max:11|unique:proveedor,rucProveedor,' . $id . ',idProveedor';
        }

        $data = $request->validate($rules);

        // Actualizar datos del proveedor
        $updateData = [
            'nombre' => $data['nombre'],
            'apellidoPaterno' => $data['apellidoPaterno'] ?? null,
            'apellidoMaterno' => $data['apellidoMaterno'] ?? null,
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'email' => $data['email'],
            'rucProveedor' => $data['rucProveedor'] ?? null,
            'estado' => $data['estado'] ?? $proveedor->estado,
        ];

        $proveedor->update($updateData);

        // Adjuntar nuevos documentos si los hay
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('proveedor/documentos/' . $proveedor->idProveedor, 'public');
                
                DocumentoProveedor::create([
                    'idProveedor' => $proveedor->idProveedor,
                    'archivo' => $path,
                    'nombre_original' => $originalName,
                    'tipo' => $file->getClientOriginalExtension(),
                    'tamano' => $file->getSize(),
                    'fecha_subida' => now(),
                ]);
            }
        }

        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor actualizado correctamente');
    }

    // 7. Eliminación lógica (cambio de estado)
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update(['estado' => 'inactivo']);

        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor desactivado correctamente');
    }

    // 8. Eliminar documento específico
    public function eliminarDocumento(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $archivoRuta = $request->input('archivo');

        // Buscar el documento en la base de datos
        $documento = DocumentoProveedor::where('idProveedor', $id)
            ->where('archivo', $archivoRuta)
            ->first();

        if ($documento) {
            // Eliminar archivo físico
            if (Storage::disk('public')->exists($documento->archivo)) {
                Storage::disk('public')->delete($documento->archivo);
            }
            
            // Eliminar registro de la base de datos
            $documento->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Documento eliminado correctamente'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Documento no encontrado'
        ], 404);
    }

    // 9. Calificación de proveedores
    public function calificar(Request $request, $id)
    {
        $data = $request->validate([
            'puntualidad' => 'required|integer|min:1|max:5',
            'calidad' => 'required|integer|min:1|max:5',
            'precio' => 'required|integer|min:1|max:5',
        ]);

        $proveedor = Proveedor::findOrFail($id);
        
        // Calcular calificación promedio
        $calificacionPromedio = round(($data['puntualidad'] + $data['calidad'] + $data['precio']) / 3, 1);
        
        $proveedor->update([
            'puntualidad' => $data['puntualidad'],
            'calidad' => $data['calidad'],
            'precio' => $data['precio'],
            'calificacion' => $calificacionPromedio,
        ]);

        return back()->with('success', 'Calificación actualizada correctamente');
    }

    // 10. Historial financiero
    public function historialFinanciero($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $compras = Compra::where('idProveedor', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $totalCompras = Compra::where('idProveedor', $id)->sum('total');
        $cantidadCompras = Compra::where('idProveedor', $id)->count();

        return view('mantenedor.proveedor.historial', compact('proveedor', 'compras', 'totalCompras', 'cantidadCompras'));
    }

    // 11. Incrementar incumplimiento y verificar bloqueo automático
    public function incrementarIncumplimiento($id, $motivo = null)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->increment('incumplimientos');
        
        // Bloqueo automático si tiene 3 o más incumplimientos
        if ($proveedor->incumplimientos >= 3) {
            $proveedor->update(['estado' => 'bloqueado']);
        }

        return back()->with('warning', "Incumplimiento registrado. Total: {$proveedor->incumplimientos}");
    }

    // 12. Dashboard individual del proveedor
    public function dashboard($id)
    {
        $proveedor = Proveedor::with('documentos')->findOrFail($id);
        $compras = Compra::where('idProveedor', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $estadisticas = [
            'total_compras' => Compra::where('idProveedor', $id)->sum('total'),
            'cantidad_compras' => Compra::where('idProveedor', $id)->count(),
            'promedio_compra' => Compra::where('idProveedor', $id)->avg('total'),
            'ultima_compra' => Compra::where('idProveedor', $id)->latest()->first(),
        ];

        $calificaciones = [
            'puntualidad' => $proveedor->puntualidad,
            'calidad' => $proveedor->calidad,
            'precio' => $proveedor->precio,
            'promedio' => $proveedor->calificacion,
        ];

        return view('mantenedor.proveedor.dashboard', compact('proveedor', 'compras', 'estadisticas', 'calificaciones'));
    }

    // 13. Activar proveedor bloqueado
    public function activar($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update([
            'estado' => 'activo',
            'incumplimientos' => 0 // Reset incumplimientos al reactivar
        ]);

        return back()->with('success', 'Proveedor reactivado correctamente');
    }

    // 14. Reporte de proveedores
    public function reporte()
    {
        $estadisticas = [
            'total' => Proveedor::count(),
            'activos' => Proveedor::where('estado', 'activo')->count(),
            'inactivos' => Proveedor::where('estado', 'inactivo')->count(),
            'bloqueados' => Proveedor::where('estado', 'bloqueado')->count(),
            'mejor_calificado' => Proveedor::where('calificacion', '>', 0)->orderBy('calificacion', 'desc')->first(),
            'con_incumplimientos' => Proveedor::where('incumplimientos', '>', 0)->count(),
        ];

        return view('mantenedor.proveedor.reporte', compact('estadisticas'));
    }

    // 15. Exportar a PDF
    public function exportarPDF()
    {
        $proveedores = Proveedor::all();
        $pdf = PDF::loadView('mantenedor.proveedor.exportarPDF', compact('proveedores'));
        return $pdf->download('proveedores_' . date('Y-m-d') . '.pdf');
    }

    // 15B. Exportar a PDF Masivo (tabla completa)
    public function exportarPDFMasivo()
    {
        $proveedores = Proveedor::all();
        $pdf = PDF::loadView('mantenedor.proveedor.exportarPDFMasivo', compact('proveedores'));
        return $pdf->download('reporte_proveedores_' . date('Y-m-d-His') . '.pdf');
    }

    // 16. Exportar a Excel
    public function exportarExcel()
    {
        return Excel::download(new ProveedorExport, 'proveedores_' . date('Y-m-d') . '.xlsx');
    }

    // 17. Búsqueda AJAX para selects dinámicos
    public function buscarAjax(Request $request)
    {
        $termino = $request->input('q');
        
        $proveedores = Proveedor::where('estado', 'activo')
            ->where(function($query) use ($termino) {
                $query->where('nombre', 'like', "%$termino%")
                      ->orWhere('apellidoPaterno', 'like', "%$termino%")
                      ->orWhere('apellidoMaterno', 'like', "%$termino%")
                      ->orWhere('rucProveedor', 'like', "%$termino%");
            })
            ->limit(10)
            ->get(['idProveedor', 'nombre', 'apellidoPaterno', 'apellidoMaterno', 'rucProveedor']);

        return response()->json($proveedores);
    }
}