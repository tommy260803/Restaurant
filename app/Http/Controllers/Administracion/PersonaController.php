<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use App\Models\Persona;
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
        $valor = trim($request->buscarpor);

        if ($valor !== '') {
            switch ($campo) {
                case 'apellidos':
                    $query->where(function ($q) use ($valor) {
                        $q->where('apellido_paterno', 'like', "%{$valor}%")
                          ->orWhere('apellido_materno', 'like', "%{$valor}%");
                    });
                    break;
                case 'todo':
                    $query->where(function ($q) use ($valor) {
                        $q->where('dni', 'like', "%{$valor}%")
                          ->orWhere('nombres', 'like', "%{$valor}%")
                          ->orWhere('apellido_paterno', 'like', "%{$valor}%")
                          ->orWhere('apellido_materno', 'like', "%{$valor}%")
                          ->orWhere('direccion', 'like', "%{$valor}%");
                    });
                    break;
                default:
                    $query->where($campo, 'like', "%{$valor}%");
                    break;
            }
        }
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
        return view('admin.persona.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8|unique:persona,dni',
            'nombres' => 'required|max:30',
            'apellido_paterno' => 'required|max:20',
            'apellido_materno' => 'required|max:20',
            'sexo' => 'required|in:M,F',
            'direccion' => 'nullable|max:100',
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
            'direccion.max' => 'El campo dirección no debe exceder 100 caracteres.',
        ]);

        Persona::create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'sexo' => $request->sexo,
            'direccion' => $request->direccion ?? '',
            'estado' => '1',
        ]);

        return redirect()->route('persona.index')->with('datos', 'Personal del restaurante registrado correctamente.');
    }

    public function edit(string $id)
    {
        $persona = Persona::findOrFail($id);
        return view('admin.persona.edit', compact('persona'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|max:30',
            'apellido_paterno' => 'required|max:20',
            'apellido_materno' => 'required|max:20',
            'sexo' => 'required|in:M,F',
            'direccion' => 'nullable|max:100',
        ], [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'nombres.max' => 'El campo nombres no debe exceder 30 caracteres.',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'apellido_paterno.max' => 'El campo apellido paterno no debe exceder 20 caracteres.',
            'apellido_materno.required' => 'El campo apellido materno es obligatorio.',
            'apellido_materno.max' => 'El campo apellido materno no debe exceder 20 caracteres.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'sexo.in' => 'El campo sexo debe ser M o F.',
            'direccion.max' => 'El campo dirección no debe exceder 100 caracteres.',
        ]);

        $persona = Persona::findOrFail($id);
        $persona->update([
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'sexo' => $request->sexo,
            'direccion' => $request->direccion ?? $persona->direccion,
        ]);

        return redirect()->route('persona.index')->with('datos', 'Personal del restaurante actualizado correctamente.');
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
            
            if (isset($data['nombres']) && isset($data['apellidoPaterno']) && isset($data['apellidoMaterno'])) {
                return response()->json($data);
            } else {
                // Si no se tiene los campos esperados, considerar como no encontrado
                return response()->json(['error' => 'DNI no encontrado o inválido'], 404);
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Errores 4xx (400, 404, 422, etc.
            return response()->json(['error' => 'DNI no encontrado'], 404);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Errores 5xx (500, 502, etc.)
            return response()->json(['error' => 'DNI no encontrado'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'DNI no encontrado'], 500);
        }
    }


    
    
}
