<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecienNacido;
use App\Models\Persona;
use App\Models\Region;
use App\Models\Provincia;
use App\Models\Distrito;

class RecienNacidoController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
    {
        $campo = $request->get('campo');
        $valor = $request->get('valor');

        $query = RecienNacido::with(['padre', 'madre']);
        if ($campo && $valor) {
            $query->where($campo, 'like', '%' . $valor . '%');
        }
        $recienNacidos = $query->orderBy('fecha_nacimiento', 'desc')
                               ->paginate(self::PAGINATION);

        return view('admin.recienNacido.index', compact('recienNacidos', 'campo', 'valor'));
    }

    public function create()
    {
        $regiones = Region::all();
        $madres = Persona::where('sexo', 'F')->get();
        $padres = Persona::where('sexo', 'M')->get();

        return view('admin.recienNacido.create', compact('regiones', 'madres', 'padres'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nombre' => 'required|max:30',
            'apellido_paterno' => 'required|max:20',
            'apellido_materno' => 'required|max:20',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'direccion_recien' => 'required|max:100',
            'region' => 'required:id_region',
            'provincia' => 'required:id_provincia',
            'id_distrito_nac' => 'required:id_distrito',
        ], [
            'nombre.required' => 'Ingrese los nombres del recién nacido.',
            'nombre.max' => 'El nombre no debe superar los 30 caracteres.',

            'apellido_paterno.required' => 'Ingrese el apellido paterno.',
            'apellido_paterno.max' => 'El apellido paterno no debe superar los 20 caracteres.',

            'apellido_materno.required' => 'Ingrese el apellido materno.',
            'apellido_materno.max' => 'El apellido materno no debe superar los 20 caracteres.',

            'sexo.required' => 'Seleccione el sexo.',

            'fecha_nacimiento.required' => 'Ingrese la fecha de nacimiento.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',

            'direccion_recien.required' => 'Ingrese la dirección.',
            'direccion_recien.max' => 'La dirección no debe superar los 100 caracteres.',

            'region.required' => 'Seleccione una región.',

            'provincia.required' => 'Seleccione una provincia.',

            'id_distrito_nac.required' => 'Seleccione un distrito.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'recienNacido')
                ->withInput()
                ->with('mostrar_modal_recien_nacido', true);
        }

        $data = $validator->validated();

        $data['direccion'] = $data['direccion_recien'];
        unset($data['direccion_recien']);

        $recienNacido = new RecienNacido();
        $recienNacido->fill($data);
        $recienNacido->save();

        return redirect()->route('nacimiento.create')->with('datos', 'Registro creado correctamente.')
            ->with('abrir_modal_recien_nacido', true);
    }

    public function edit($id)
    {
        $recienNacido = RecienNacido::with(['padre', 'madre', 'distrito.provincia.region'])
            ->where('id_recien_nacido', $id)
            ->firstOrFail();

        // Obtener regiones
        $regiones = Region::all();
        
        // Si el recién nacido tiene distrito, obtener la provincia y región relacionadas
        if ($recienNacido->distrito) {
            $provincias = Provincia::where('id_region', $recienNacido->distrito->provincia->id_region)->get();
            $distritos = Distrito::where('id_provincia', $recienNacido->distrito->id_provincia)->get();
        } else {
            $provincias = collect();
            $distritos = collect();
        }

        // Obtener madres y padres
        $madres = Persona::where('sexo', 'F')->get();
        $padres = Persona::where('sexo', 'M')->get();

        return view('admin.recienNacido.edit', compact(
            'recienNacido', 'regiones', 'provincias', 'distritos', 'madres', 'padres'
        ));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nombre' => 'required|max:100',
            'apellido_paterno' => 'required|max:100',
            'apellido_materno' => 'required|max:100',
            'fecha_nacimiento' => 'required|date',
            'hora_nacimiento' => 'required',
            'dni_padre' => 'nullable|digits:8|exists:persona,dni',
            'dni_madre' => 'nullable|digits:8|exists:persona,dni',
            'direccion' => 'required|max:255',
            'id_distrito_nac' => 'required|size:6',
        ]);

        $recienNacido = RecienNacido::findOrFail($id);
        $recienNacido->fill($data);
        $recienNacido->save();

        return redirect()->route('recienNacido.index')->with('datos', 'Registro actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        try {
            $recienNacido = RecienNacido::findOrFail($id);
            // Eliminar el registro completamente de la base de datos
            $recienNacido->delete();
            
            return redirect()->route('recienNacido.index')
                           ->with('datos', 'Registro eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('recienNacido.index')
                           ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    public function confirmar(string $id)
    {
        $recienNacido = RecienNacido::findOrFail($id);
        return view('admin.recienNacido.confirmar', compact('recienNacido'));
    }
}