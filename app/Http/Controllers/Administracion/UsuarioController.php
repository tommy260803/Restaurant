<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Usuario, Persona, Administrador, Registrador, Region, Provincia, Distrito, Notificacion};
use Illuminate\Support\Facades\{Storage, Hash};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $usuarios = Usuario::where('estado', '1')
            ->where('nombre_usuario', 'like', "%{$buscarpor}%")
            ->paginate(self::PAGINATION);

        return view('admin.usuario.index', compact('usuarios', 'buscarpor'));
    }

    public function create()
    {
        $personasConUsuario = Usuario::pluck('dni_usuario');
        $personas = Persona::where('estado', '1')
            ->whereNotIn('dni', $personasConUsuario)
            ->get();

        return view('admin.usuario.create', compact('personas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni_usuario' => 'required|exists:persona,dni|unique:usuarios,dni_usuario',
            'nombre_usuario' => 'required|max:30|unique:usuarios,nombre_usuario',
            'contrasena' => 'required|min:6',
            'email_mi_acta' => 'required|email|unique:usuarios,email_mi_acta',
            'email_respaldo' => 'nullable|email',
            'rol' => 'required|in:Administrador,Registrador',
        ], [
            'dni_usuario.required' => 'Debe seleccionar un DNI válido.',
            'dni_usuario.exists' => 'El DNI no está registrado en la tabla personas.',
            'dni_usuario.unique' => 'Ya existe un usuario con este DNI.',
            'nombre_usuario.required' => 'El nombre de usuario es obligatorio.',
            'nombre_usuario.unique' => 'Este nombre de usuario ya está en uso.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'email_mi_acta.required' => 'Debe ingresar un correo electrónico.',
            'email_mi_acta.unique' => 'Este correo ya está registrado.',
            'email_respaldo.email' => 'El correo de respaldo debe ser válido.',
            'rol.required' => 'Debe seleccionar un rol.',
        ]);

        $usuario = Usuario::create([
            'dni_usuario' => $request->dni_usuario,
            'nombre_usuario' => $request->nombre_usuario,
            'contrasena' => bcrypt($request->contrasena),
            'email_mi_acta' => $request->email_mi_acta,
            'email_respaldo' => $request->email_respaldo,
            'rol' => $request->rol,
            'estado' => '1',
        ]);

        if (method_exists($usuario, 'assignRole')) {
            $usuario->assignRole($request->rol);
        }

        // Creación de registro asociado en tabla de roles específicos
        if ($request->rol === 'Administrador') {
            \App\Models\Administrador::create([
                'id_usuario' => $usuario->id_usuario,
                'estado' => '1',
            ]);
        } elseif ($request->rol === 'Registrador') {
            \App\Models\Registrador::create([
                'id_usuario' => $usuario->id_usuario,
                'estado' => '1',
            ]);
        }

        return redirect()->route('usuarios.index')
                        ->with('datos', 'Usuario registrado correctamente.');
    }


    public function edit($id)
    {
        $usuarios = Usuario::findOrFail($id);
        $personas = Persona::where('estado', '1')->get();

        return view('admin.usuario.create', compact('usuarios', 'personas'));
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            // Validaciones
            if ($request->hasFile('foto')) {
            }
            $data = $request->validate([
                'nombre_usuario' => 'required|max:30',
                'email_mi_acta' => 'required|email|unique:usuarios,email_mi_acta,' . $usuario->id_usuario . ',id_usuario',
                'email_respaldo' => 'nullable|email',
                'contrasena' => 'nullable|min:6',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072', // 3MB
                'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
            ], [
                'nombre_usuario.required' => 'El nombre de usuario es obligatorio.',
                'nombre_usuario.max' => 'El nombre de usuario no puede tener más de 30 caracteres.',
                'email_mi_acta.required' => 'El email principal es obligatorio.',
                'email_mi_acta.email' => 'El email principal debe ser válido.',
                'email_mi_acta.unique' => 'Este email ya está en uso por otro usuario.',
                'email_respaldo.email' => 'El email de respaldo debe ser válido.',
                'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'foto.image' => 'El archivo debe ser una imagen.',
                'foto.mimes' => 'La foto debe ser jpg, jpeg, png o webp.',
                'foto.max' => 'La foto no debe superar los 3MB.',
                'portada.image' => 'El archivo debe ser una imagen.',
                'portada.mimes' => 'La portada debe ser jpg, jpeg, png o webp.',
                'portada.max' => 'La portada no debe superar los 5MB.',
            ]);

            $usuario->nombre_usuario = $request->nombre_usuario;
            $usuario->email_mi_acta = $request->email_mi_acta;
            $usuario->email_respaldo = $request->email_respaldo;

            // Actualizar contraseña solo si se proporciona en el formulario
            if ($request->filled('contrasena')) {
                $usuario->contrasena = Hash::make($request->contrasena);
            }

            // Manejo de la foto de perfil
            if ($request->hasFile('foto')) {
                // Eliminar foto anterior si existe del storage
                if ($usuario->foto && Storage::disk('public')->exists($usuario->foto)) {
                    Storage::disk('public')->delete($usuario->foto);
                }
                
                // Guardar nueva foto en el storage
                $fotoPath = $request->file('foto')->store('fotos_usuarios', 'public');
                $usuario->foto = $fotoPath;
            }

            // Manejo de la portada
            if ($request->hasFile('portada')) {
                // Eliminar portada anterior si existe
                if ($usuario->portada && Storage::disk('public')->exists($usuario->portada)) {
                    Storage::disk('public')->delete($usuario->portada);
                }
                
                // Guardar nueva portada
                $portadaPath = $request->file('portada')->store('portadas_usuarios', 'public');
                $usuario->portada = $portadaPath;
            }

            // Guardar cambios
            $usuario->save();

            return redirect()->route('usuarios.perfil', $usuario->id_usuario)
                           ->with('success', 'Perfil actualizado correctamente.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error al actualizar cuenta de usuario: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al actualizar el perfil. Por favor, intenta nuevamente.')
                        ->withInput();
        }
    }

    // PERFIL
    public function perfil($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuario.form-perfil.datosPerfil', compact('usuario'));
    }

    public function actualizarPerfil(Request $request, $id)
    {
        return $this->update($request, $id); // reutiliza lógica
    }

    // CUENTA
    // Método para mostrar la vista de cuenta
public function cuenta($id)
{
    $usuario = Usuario::with(['persona.distrito.provincia.region'])->findOrFail($id);
    
    // Verificar que el usuario solo puede ver su propia cuenta
    if (auth()->user()->id_usuario != $usuario->id_usuario) {
        return redirect()->route('home')->with('error', 'No tiene permisos para acceder a esta sección.');
    }

    // Cargar datos geográficos
    $regiones = Region::orderBy('nombre')->get();
    $provincias = Provincia::orderBy('nombre')->get();
    $distritos = Distrito::orderBy('nombre')->get();

    return view('admin.usuario.form-perfil.datosCuenta', compact('usuario', 'regiones', 'provincias', 'distritos'));
}

// Métodos auxiliares para AJAX (ya están en UbigeoController pero los duplico por si acaso)
public function getProvincias($regionId)
{
    try {
        $provincias = Provincia::where('id_region', $regionId)
                                          ->select('id_provincia', 'nombre')
                                          ->orderBy('nombre')
                                          ->get();

        return response()->json($provincias);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al cargar provincias'], 500);
    }
}

public function getDistritos($provinciaId)
{
    try {
        $distritos = Distrito::where('id_provincia', $provinciaId)
                                        ->select('id_distrito', 'nombre')
                                        ->orderBy('nombre')
                                        ->get();

        return response()->json($distritos);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al cargar distritos'], 500);
    }
}

    public function actualizarCuenta(Request $request, $id)
{
    $usuario = Usuario::with('persona')->findOrFail($id);

    // Verificar que el usuario solo puede editar su propia cuenta
    if (auth()->user()->id_usuario != $usuario->id_usuario) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para realizar esta acción.',
            ], 403);
        }
        return redirect()->back()->with('error', 'No tiene permisos para realizar esta acción.');
    }

    // Validación de datos editables
    $data = $request->validate([
        'estado_civil' => 'nullable|in:Soltero,Casado,Divorciado,Viudo',
        'email_respaldo' => 'nullable|email|unique:usuarios,email_respaldo,' . $usuario->id_usuario . ',id_usuario',
        'id_region' => 'nullable|exists:region,id_region',
        'id_provincia' => 'nullable|exists:provincia,id_provincia',
        'id_distrito' => 'nullable|exists:distrito,id_distrito',
        'direccion' => 'nullable|string|max:255',
    ]);

    // Validación adicional: verificar que provincia pertenece a la región
    if ($request->filled('id_region') && $request->filled('id_provincia')) {
        $provincia = Provincia::where('id_provincia', $request->id_provincia)
                                        ->where('id_region', $request->id_region)
                                        ->first();
        if (!$provincia) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La provincia seleccionada no pertenece a la región indicada.',
                    'errors' => [
                        'id_provincia' => ['La provincia seleccionada no es válida para esta región.']
                    ]
                ], 422);
            }
            return redirect()->back()->withErrors(['id_provincia' => 'La provincia seleccionada no es válida para esta región.'])->withInput();
        }
    }

    // Validación adicional: verificar que distrito pertenece a la provincia
    if ($request->filled('id_provincia') && $request->filled('id_distrito')) {
        $distrito = Distrito::where('id_distrito', $request->id_distrito)
                                      ->where('id_provincia', $request->id_provincia)
                                      ->first();
        if (!$distrito) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El distrito seleccionado no pertenece a la provincia indicada.',
                    'errors' => [
                        'id_distrito' => ['El distrito seleccionado no es válido para esta provincia.']
                    ]
                ], 422);
            }
            return redirect()->back()->withErrors(['id_distrito' => 'El distrito seleccionado no es válido para esta provincia.'])->withInput();
        }
    }

    try {
        // Iniciar transacción
        DB::beginTransaction();

        // Actualizar datos del usuario
        $usuario->update([
            'email_respaldo' => $data['email_respaldo'] ?? $usuario->email_respaldo,
        ]);

        // Actualizar datos de la persona
        if ($usuario->persona) {
            $personaData = [];
            
            if (isset($data['estado_civil'])) {
                $personaData['estado_civil'] = $data['estado_civil'];
            }
            
            
            if (isset($data['id_distrito'])) {
                $personaData['id_distrito'] = $data['id_distrito'];
            }
            
            if (isset($data['direccion'])) {
                $personaData['direccion'] = $data['direccion'];
            }

            if (!empty($personaData)) {
                $usuario->persona->update($personaData);
            }
        }

        // Confirmar transacción
        DB::commit();

        // Respuesta según el tipo de request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado correctamente. Los cambios se han guardado exitosamente.',
                'data' => [
                    'usuario' => $usuario->fresh(['persona.distrito.provincia.region']),
                    'timestamp' => now()->format('d/m/Y H:i:s')
                ]
            ]);
        }

        return redirect()->route('usuarios.cuenta', $usuario->id_usuario)->with('success', 'Perfil actualizado correctamente.');

    } catch (\Exception $e) {
        // Revertir transacción en caso de error
        DB::rollBack();
        
        // Log del error para debugging
        \Log::error('Error al actualizar perfil de usuario: ' . $e->getMessage(), [
            'user_id' => $usuario->id_usuario,
            'data' => $data,
            'trace' => $e->getTraceAsString()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Por favor, intente nuevamente.',
                'errors' => []
            ], 500);
        }

        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Error al actualizar el perfil. Por favor, intente nuevamente.');
    }
}

    // NOTIFICACIONES
    public function notificaciones($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Verifica que el usuario autenticado esté viendo solo sus propias notificaciones
        if (Auth::id() !== $usuario->id_usuario && !Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        // Lógica para cargar las notificaciones del usuario
        $query = Notificacion::where('usuario_id', $usuario->id_usuario);

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estadísticas simples
        $estadisticas = [
            'no_leidas' => $query->where('leida', false)->count(),
            'por_tipo' => [
                'validacion' => Notificacion::where('usuario_id', $usuario->id_usuario)->where('tipo', 'validacion')->count(),
                'pago' => Notificacion::where('usuario_id', $usuario->id_usuario)->where('tipo', 'pago')->count(),
            ],
            'leidas_hoy' => Notificacion::where('usuario_id', $usuario->id_usuario)
                ->whereDate('created_at', now()->toDateString())
                ->count(),
        ];

        return view('admin.usuario.form-perfil.datosNotificacion', [
            'usuario' => $usuario,
            'notificaciones' => $notificaciones,
            'notificaciones_no_leidas' => $estadisticas['no_leidas'],
            'validaciones_pendientes' => $estadisticas['por_tipo']['validacion'],
            'pagos_pendientes' => $estadisticas['por_tipo']['pago'],
            'tramites_hoy' => $estadisticas['leidas_hoy']
        ]);
    }

    public function actualizarNotificaciones(Request $request, $id)
    {
        // Implementa lógica según tus campos
        return back()->with('success', 'Preferencias de notificación actualizadas.');
    }

    public function confirmar($id)
    {
        $usuarios = Usuario::findOrFail($id);
        return view('admin.usuario.confirmar', compact('usuarios'));
    }

    public function destroy($id)
    {
        $usuarios = Usuario::findOrFail($id);
        $usuarios->estado = '0';
        $usuarios->save();
        return redirect()->route('usuarios.index')->with('datos', 'Registro eliminado...!');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }
}
