<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use App\Models\Persona;
use App\Models\Region;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class PersonaController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
{
    $query = Persona::query();

    if ($request->filled('buscarpor') && $request->filled('buscar_por')) {
        $campo = $request->buscar_por;
        $valor = $request->buscarpor;
        $query->where($campo, 'like', "%{$valor}%");
    }
    

    $personas = $query->paginate(10);

    // Si es petición AJAX (por búsqueda dinámica), solo devuelve la tabla
    if ($request->ajax()) {
        return view('admin.persona.partials.table', ['personas' => $personas])->render();
    }

    return view('admin.persona.index', ['persona' => $personas]);
}

public function show($id)
{
    $persona = Persona::findOrFail($id);
    return view('admin.persona.show', compact('persona'));
}


    public function create()
    {
        $regiones = Region::all();
        return view('admin.persona.create', compact( 'regiones'));
    }

    public function store(Request $request)
{
    $data = request()->validate([
        'dni' => 'required|digits:8|unique:persona,dni',
        'nombres' => 'required|max:30',
        'apellido_paterno' => 'required|max:20',
        'apellido_materno' => 'required|max:20',
        'sexo' => 'required|in:M,F',
        'fecha_nacimiento' => 'required|date',
        'id_distrito' => 'required|size:6',
        'nacionalidad' => 'required|max:20',
        'estado_civil' => 'required|max:30',
    ], [
        'dni.required' => 'El campo DNI es obligatorio.',
        'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
        'dni.unique' => 'El DNI ya está registrado.',
        'nombres.required' => 'El campo nombres es obligatorio.',
        'nombres.max' => 'El campo nombres no debe exceder 30 caracteres.',
        'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
        'apellido_paterno.max' => 'El campo apellido paterno no debe exceder 20 caracteres.',
        'apellido_materno.required' => 'El campo apellido materno es obligatorio.',
        'apellido_materno.max' => 'El campo apellido materno no debe exceder 20 caracteres.',
        'sexo.required' => 'El campo sexo es obligatorio.',
        'sexo.in' => 'El campo sexo debe ser M o F.',
        'fecha_nacimiento.required' => 'El campo fecha de nacimiento es obligatorio.',
        'fecha_nacimiento.date' => 'Debe ingresar una fecha válida.',
        'id_distrito.required' => 'El campo distrito es obligatorio.',
        'id_distrito.size' => 'El código de distrito debe tener 6 caracteres.',
        'nacionalidad.required' => 'El campo nacionalidad es obligatorio.',
        'nacionalidad.max' => 'El campo nacionalidad no debe exceder 20 caracteres.',
        'estado_civil.required' => 'El campo estado civil es obligatorio.',
        'estado_civil.max' => 'El campo estado civil no debe exceder 30 caracteres.',
    ]);

    $persona = new Persona();
    $persona->dni = $request->dni;
    $persona->nombres = $request->nombres;
    $persona->apellido_paterno = $request->apellido_paterno;
    $persona->apellido_materno = $request->apellido_materno;
    $persona->sexo = $request->sexo;
    $persona->fecha_nacimiento = $request->fecha_nacimiento;
    $persona->id_distrito = $request->id_distrito;
    $persona->nacionalidad = $request->nacionalidad;
    $persona->estado_civil = $request->estado_civil;
    $persona->estado = '1';
    $persona->save();
    return redirect()->route('persona.index')->with('datos', 'Persona registrada correctamente.');
}

    public function edit(string $id)
    {
        $persona = Persona::findOrFail($id);
        $regiones = Region::all();
        $provincias = $persona->distrito ? Provincia::where('id_region', $persona->distrito->provincia->id_region)->get() : collect();
        $distritos = $persona->distrito ? Distrito::where('id_provincia', $persona->distrito->id_provincia)->get() : collect();
        return view('admin.persona.edit', compact('persona', 'regiones', 'provincias', 'distritos'));
    }

    public function update(Request $request,$id)
    {
        $data = $request->validate([
            'nombres' => 'required|max:30',
            'apellido_paterno' => 'required|max:20',
            'apellido_materno' => 'required|max:20',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required|max:20',
            'estado_civil' => 'required|in:Soltero,Divorciado,Casado,Viudo',
        ], [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'nombres.max' => 'El campo nombres no debe exceder 30 caracteres.',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'apellido_paterno.max' => 'El campo apellido paterno no debe exceder 20 caracteres.',
            'apellido_materno.required' => 'El campo apellido materno es obligatorio.',
            'apellido_materno.max' => 'El campo apellido materno no debe exceder 20 caracteres.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'sexo.in' => 'El campo sexo debe ser M o F.',
            'fecha_nacimiento.required' => 'El campo fecha de nacimiento es obligatorio.',
            'fecha_nacimiento.date' => 'Debe ingresar una fecha válida.',
            'nacionalidad.required' => 'El campo nacionalidad es obligatorio.',
            'nacionalidad.max' => 'El campo nacionalidad no debe exceder 20 caracteres.',
            'estado_civil.required' => 'El campo estado civil es obligatorio.',
            'estado_civil.in' => 'El estado civil debe ser Soltero, Divorciado, Casado o Viudo.',
        ]);

        $persona = Persona::findOrFail($id);
        $persona->dni = $request->dni;
        $persona->nombres = $request->nombres;
        $persona->apellido_paterno = $request->apellido_paterno;
        $persona->apellido_materno = $request->apellido_materno;
        $persona->sexo = $request->sexo;
        $persona->fecha_nacimiento = $request->fecha_nacimiento;
        $persona->id_distrito = $request->id_distrito;
        $persona->nacionalidad = $request->nacionalidad;
        $persona->estado_civil = $request->estado_civil;
        $persona->save();

        return redirect()->route('persona.index')->with('datos', 'Persona actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $persona = Persona::findOrFail($id);
        $persona->estado = '0';
        $persona->save();
        $usuario = session('usuario');
        return redirect()->route('persona.index', compact('usuario'))->with('datos', 'Persona eliminada correctamente.');
    }

    public function confirmar($id)
    {
        $persona = Persona::findOrFail($id);
        $usuario = session('usuario');
        return view('admin.persona.confirmar', compact('persona', 'usuario'));
    }
    

public function consultarDni($dni): JsonResponse
    {
        $token = env('API_RENIEC_TOKEN', '');

        $client = new Client([
            'base_uri' => 'https://api.apis.net.pe',
            'verify' => false,
            'timeout' => 5,
        ]);

        try {
            $response = $client->request('GET', '/v2/reniec/dni', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'query' => ['numero' => $dni],
            ]);

            $data = json_decode($response->getBody(), true);
            \Log::info('Respuesta RENIEC API:', $data);
            
            if (isset($data['nombres']) && isset($data['apellidoPaterno']) && isset($data['apellidoMaterno'])) {
                return response()->json($data);
            } else {
                // Si no se tiene los campos esperados, considerar como no encontrado
                return response()->json(['error' => 'DNI no encontrado o inválido'], 404);
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Errores 4xx (400, 404, 422, etc.)
            \Log::error('Respuesta: ' . $e->getResponse()->getBody());
            \Log::error('Error ClientException: ' . $e->getMessage());
            \Log::error('Código de estado: ' . $e->getResponse()->getStatusCode());
            \Log::error('Respuesta: ' . $e->getResponse()->getBody());
            return response()->json(['error' => 'DNI no encontrado'], 404);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Errores 5xx (500, 502, etc.)
            \Log::error('Error ServerException: ' . $e->getMessage());
            return response()->json(['error' => 'DNI no encontrado'], 500);
        } catch (\Exception $e) {
            \Log::error('Error general en consulta DNI: ' . $e->getMessage());
            return response()->json(['error' => 'DNI no encontrado'], 500);
        }
    }


    
    
}
