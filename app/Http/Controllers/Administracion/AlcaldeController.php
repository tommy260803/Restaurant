<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Alcalde;
use Illuminate\Support\Str;
use App\Models\Administrador;
use Illuminate\Support\Facades\Storage;
use App\Models\Persona;
use App\Models\Region;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;

class AlcaldeController extends Controller
{
    public function index(Request $request)
    {
        $query = Alcalde::with(['persona.distrito.provincia.region', 'administrador.usuario.persona']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('persona', function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                  ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%");
            });
        }

        switch ($request->get('ordenar', 'fecha_inicio_desc')) {
            case 'fecha_inicio_asc':
                $query->orderBy('fecha_inicio', 'asc');
                break;
            case 'nombre_asc':
                $query->join('persona', 'alcalde.dni_alcalde', '=', 'persona.dni')
                      ->orderBy('persona.nombres', 'asc')
                      ->select('alcalde.*');
                break;
            default:
                $query->orderBy('fecha_inicio', 'desc');
        }

        $alcaldes = $query->paginate(10);

        $alcaldeActivo = Alcalde::where('estado', 1)
            ->where('fecha_fin', '>=', now()->format('Y-m-d'))
            ->exists();

        return view('admin.alcalde.index', compact('alcaldes', 'alcaldeActivo'));
    }

    public function create()
    {
        $usuario = auth()->user();
        if (!$usuario->administrador) {
            return redirect()->route('alcalde.index')->with('error', 'No tienes permisos para registrar alcaldes.');
        }

        $alcaldeActivo = Alcalde::where('estado', 1)
            ->where('fecha_fin', '>=', now()->format('Y-m-d'))
            ->exists();

        if ($alcaldeActivo) {
            return redirect()->route('alcalde.index')->with('error', 'Ya existe un alcalde activo.');
        }

        $personas = Persona::whereDoesntHave('alcalde', function ($q) {
        $q->where('estado', 1)
          ->where('fecha_fin', '>=', now()->format('Y-m-d'));
        })
        ->whereDoesntHave('usuario.administrador')
        ->where('dni', '!=', auth()->user()->persona->dni)
        ->get();

        if ($personas->isEmpty()) {
            return redirect()->route('alcalde.index')->with('error', 'No hay personas disponibles para registrar como alcalde.');
        }

        $administrador = $usuario->administrador;

        return view('admin.alcalde.create', compact('personas', 'administrador'));
    }

    public function store(Request $request)
{
    try {
        $usuario = auth()->user();

        // Validaciones
        $request->validate([
            'dni_alcalde' => 'required|exists:persona,dni',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:0,1',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'foto.required' => 'Debe subir una foto de perfil.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'Solo se permiten imágenes JPG, JPEG, PNG o WEBP.',
            'foto.max' => 'La imagen no debe superar los 3MB.',
        ]);

        if (!$usuario->administrador) {
            return back()->withErrors(['general' => 'No tienes un administrador asignado.'])->withInput();
        }

        if ($request->dni_alcalde == $usuario->persona->dni) {
            return back()->withErrors(['dni_alcalde' => 'No puedes registrarte a ti mismo como alcalde.'])->withInput();
        }

        // Calcular fecha de fin
        $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio);
        $fechaFin = $fechaInicio->copy()->addYears(5);

        // Guardar la foto
        $fotoPath = $request->file('foto')->store('fotos_alcaldes', 'public');

        // Crear el alcalde
        Alcalde::create([
            'dni_alcalde' => $request->dni_alcalde,
            'id_administrador' => $usuario->administrador->id_administrador,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $request->estado,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('alcalde.index')->with('success', 'Alcalde registrado correctamente.');
    
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    
    } catch (\Exception $e) {
        Log::error('❌ Error al registrar alcalde: ' . $e->getMessage());
        return back()->with('error', 'Error inesperado al registrar el alcalde.')->withInput();
    }
}

    public function edit($id)
    {
        $alcalde = Alcalde::with('persona.distrito.provincia.region', 'administrador.usuario.persona')->findOrFail($id);
        $regiones = Region::all();
        $provincia = $alcalde->persona->distrito->provincia;
        $provincias = Provincia::where('id_region', $provincia->id_region)->get();
        $distritos = Distrito::where('id_provincia', $provincia->id_provincia)->get();

        return view('admin.alcalde.edit', compact('alcalde', 'regiones', 'provincias', 'distritos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:100',
            'idRegion' => 'required|exists:region,id_region',
            'idProvincia' => 'required|exists:provincia,id_provincia',
            'idDistrito' => 'required|exists:distrito,id_distrito',
            'estadoCivil' => 'required|in:Soltero,Casado,Viudo,Divorciado',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|boolean',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $alcalde = Alcalde::findOrFail($id);
        $persona = $alcalde->persona;
        if ($request->hasFile('foto')) {
            // Eliminar la imagen anterior si existe
            if ($alcalde->foto && Storage::disk('public')->exists($alcalde->foto)) {
                Storage::disk('public')->delete($alcalde->foto);
            }

            // Guardar la nueva foto
            $fotoPath = $request->file('foto')->store('fotos_alcaldes', 'public');
            $alcalde->foto = $fotoPath;
        }
        $persona->update([
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'nacionalidad' => $request->nacionalidad,
            'estado_civil' => $request->estadoCivil,
            'id_distrito' => $request->idDistrito,
        ]);

        $alcalde->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
        ]);

        return redirect()->route('alcalde.index')->with('success', 'Alcalde actualizado correctamente.');
    }

    public function destroy($id)
    {
        $alcalde = Alcalde::findOrFail($id);
        $alcalde->update(['estado' => '0']);

        return redirect()->route('alcalde.index')->with('success', 'Alcalde eliminado correctamente.');
    }

    public function show($id)
    {
        $alcalde = Alcalde::with(['persona.distrito.provincia.region', 'administrador.usuario.persona'])->findOrFail($id);
        return view('admin.alcalde.show', compact('alcalde'));
    }
}
